<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Events\ReportNew;
use App\Mail\AtRiskAlert;
use App\Models\Investigation;
use App\Models\Report;
use App\Models\User;
use App\Rules\MirahezeUsernameRule;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Mail;

/**
 * Controller for all Report actions
 */
class ReportController extends Controller
{
	/**
	 * Indexes all reports, filtering for non-privileged users
	 *
	 *
	 * @return Application|Factory|View
	 */
	public function index( Request $request )
	{
		$allReports = Report::all();

		if ( !$request->user()->hasFlag( 'ts' ) ) {
			$allReports = $allReports->where( 'reporter', $request->user() );
		}

		$query = $request->query();

		foreach ( $query as $type => $key ) {
			if ( !$key ) {
				continue;
			} elseif ( in_array( $type, ['user', 'reporter'], true ) ) {
				$allReports = $allReports->where( $type, User::findById( (int)$key ) );
			} elseif ( in_array( $type, ['investigation', 'type'], true ) ) {
				$allReports = $allReports->where( $type, $key );
			}
		}

		if ( $request->input( 'closed' ) ) {
			$allReports = $allReports->whereNotNull( 'reviewed' );
		} else {
			$allReports = $allReports->whereNull( 'reviewed' );
		}

		return view( 'reports' )
			->with( 'reports', $allReports );
	}

	/**
	 * Stores a new report once made
	 *
	 *
	 * @return Application|RedirectResponse|Redirector
	 */
	public function store( Report $report, Request $request )
	{
		$request->validate(
			[
				'username' => [new MirahezeUsernameRule],
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

		$event = ( count( $subjectUser->events ) === 0 ) ? 'created-report' : 'new-report';

		$subjectUser->newEvent( $event, $report->id );

		$request->user()->newEvent( 'filed-report', $report->id );

		if ( config( 'app.atrisk' ) && $request->input( 'at' ) ) {
			Mail::to( config( 'app.atrisk' ) )->send( new AtRiskAlert( $report ) );
		}

		ReportNew::dispatch( $newReport );

		request()->session()->flash( 'successFlash', __( 'report' ) . ' ' . __( 'toast-submitted' ) );

		return redirect( '/reports' );
	}

	/**
	 * Shows the creation form for a new report
	 *
	 * @return Application|Factory|View
	 */
	public function create()
	{
		return view( 'report.new' );
	}

	/**
	 * Shows a specific report
	 *
	 *
	 * @return Application|Factory|View
	 */
	public function show( Report $report )
	{
		return view( 'report.view' )->with( 'report', $report );
	}

	/**
	 * Processor for handling a change in a reports state
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
			$report->update( [
				'reviewed' => now(),
			] );
		}

		request()->session()->flash( 'successFlash', __( 'report' ) . ' ' . __( 'toast-updated' ) );

		return back();
	}
}
