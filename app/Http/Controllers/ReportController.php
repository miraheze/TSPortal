<?php

declare( strict_types = 1 );

namespace App\Http\Controllers;

use App\Events\ReportNew;
use App\Mail\AtRiskAlert;
use App\Models\Investigation;
use App\Models\Report;
use App\Models\User;
use App\Rules\MirahezeUsernameRule;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

/**
 * Controller for all Report actions.
 */
class ReportController
{
	/**
	 * Indexes all reports, filtering for non-privileged users.
	 */
	public function index( Request $request ): View
	{
		$query = Report::query();
		if ( !$request->user()->hasFlag( 'ts' ) ) {
			$query->where( 'reporter', $request->user()->id );
		}

		foreach ( $request->query() as $type => $key ) {
			if ( !$key ) {
				continue;
			} elseif ( in_array( $type, [ 'user', 'reporter' ], true ) ) {
				$query->where( $type, (int)$key );
			} elseif ( in_array( $type, [ 'investigation', 'type' ], true ) ) {
				$query->where( $type, $key );
			}
		}

		if ( $request->input( 'closed' ) ) {
			$query->whereNotNull( 'reviewed' );
		} elseif ( $request->input( 'reporter' ) === null && $request->input( 'user' ) === null ) {
			$query->whereNull( 'reviewed' );
		}

		return view( 'reports' )->with( 'reports', $query->get() );
	}

	/**
	 * Stores a new report once made.
	 */
	public function store( Report $report, Request $request ): RedirectResponse
	{
		$request->validate(
			[
				'username' => [ new MirahezeUsernameRule ],
			]
		);

		$subjectUser = User::findOrCreate( $request->input( 'username' ) );
		$newReport = $report::factory()->create(
			[
				'type' => $request->input( 'report' ),
				'user' => $subjectUser,
				'reporter' => $request->user(),
				'text' => $request->input( 'evidence' ),
			]
		);

		$event = $subjectUser->events()->exists() ? 'new-report' : 'created-report';
		$subjectUser->newEvent( $event, $report->id );

		$request->user()->newEvent( 'filed-report', $report->id );
		if ( config( 'app.atrisk' ) && $request->input( 'at' ) ) {
			Mail::to( config( 'app.atrisk' ) )->send( new AtRiskAlert( $report ) );
		}

		ReportNew::dispatch( $newReport );
		$request->session()->flash( 'successFlash', __( 'report' ) . ' ' . __( 'toast-submitted' ) );

		return redirect( '/reports' );
	}

	/**
	 * Shows the creation form for a new report.
	 */
	public function create(): View
	{
		return view( 'report.new' );
	}

	/**
	 * Shows a specific report.
	 */
	public function show( Report $report ): View
	{
		return view( 'report.view' )->with( 'report', $report );
	}

	/**
	 * Processor for handling a change in a reports state.
	 */
	public function update( Report $report, Request $request ): RedirectResponse
	{
		if ( $request->input( 'investigate' ) ?? false ) {
			$investigation = Investigation::factory()->create( [
				'subject' => $report->user,
				'created' => now(),
				'assigned' => $request->user(),
			] );

			$report->update( [
				'investigation' => $investigation->id,
				'reviewed' => now(),
			] );
		} elseif ( $request->input( 'close' ) ?? false ) {
			$report->update( [ 'reviewed' => now() ] );
		}

		$request->session()->flash( 'successFlash', __( 'report' ) . ' ' . __( 'toast-updated' ) );
		return back();
	}
}
