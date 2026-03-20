<?php

namespace App\Http\Controllers;

use App\Events\AppealNew;
use App\Events\InvestigationClosed;
use App\Events\InvestigationNew;
use App\Events\InvestigationReopened;
use App\Models\Appeal;
use App\Models\Investigation;
use App\Models\User;
use App\Rules\MirahezeUsernameRule;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Controller class for all Investigation actions.
 */
class InvestigationController
{
	/**
	 * Indexes all investigations, with filters for non-privileged users.
	 */
	public function index( Request $request ): View
	{
		$allInvestigations = Investigation::all();
		$query = $request->query();

		foreach ( $query as $type => $key ) {
			if ( !$key ) {
				continue;
			} elseif ( in_array( $type, [ 'subject', 'assigned' ], true ) ) {
				$allInvestigations = $allInvestigations->where( $type, User::findById( (int)$key ) );
			} elseif ( in_array( $type, [ 'type', 'recommendation' ], true ) ) {
				if ( $key === 'unknown' ) {
					$key = null;
				}

				$allInvestigations = $allInvestigations->where( $type, $key );
			}
		}

		if ( $request->input( 'closed' ) ) {
			$allInvestigations = $allInvestigations->whereNotNull( 'closed' );
		} elseif ( $request->input( 'assigned' ) === null && $request->input( 'subject' ) === null ) {
			$allInvestigations = $allInvestigations->whereNull( 'closed' );
		}

		return view( 'investigations' )->with( 'investigations', $allInvestigations );
	}

	/**
	 * Stores a new investigation once created.
	 */
	public function store( Investigation $investigation, Request $request ): RedirectResponse
	{
		$request->validate(
			[
				'username' => [ 'required', 'string', new MirahezeUsernameRule ],
			]
		);

		$investigationUser = User::findOrCreate( $request->input( 'username' ) );
		$newInvestigation = $investigation::factory()->create(
			[
				'type' => $request->input( 'topic' ),
				'text' => $request->input( 'evidence' ),
				'recommendation' => $request->input( 'recommend' ),
				'explanation' => $request->input( 'justify' ),
				'subject' => $investigationUser,
				'assigned' => $request->user(),
			]
		);

		$event = ( count( $investigationUser->events ) === 0 ) ? 'created-investigation' : 'new-investigation';
		$investigationUser->newEvent( $event );

		InvestigationNew::dispatch( $newInvestigation );
		$request->session()->flash( 'successFlash', __( 'investigation' ) . ' ' . __( 'toast-submitted' ) );

		return redirect( '/investigations' );
	}

	/**
	 * Shows creation form for a new investigation.
	 */
	public function create(): View
	{
		return view( 'investigation.new' );
	}

	/**
	 * Shows a specific investigation.
	 */
	public function show( Investigation $investigation ): View
	{
		return view( 'investigation.view' )->with( 'investigation', $investigation );
	}

	/**
	 * Form for editing an investigation.
	 */
	public function edit( Investigation $investigation ): View
	{
		return view( 'investigation.edit' )->with( 'investigation', $investigation );
	}

	/**
	 * Processor for processing updates to an investigation.
	 */
	public function update( Investigation $investigation, Request $request ): RedirectResponse
	{
		if ( $request->input( 'event' ) === null ) {
			$updates = [
				'type' => $request->input( 'topic' ),
				'text' => $request->input( 'evidence' ),
				'recommendation' => $request->input( 'recommend' ),
				'explanation' => $request->input( 'justify' ),
			];

			if ( $request->input( 'assign' ) !== null ) {
				$updates['assigned'] = $request->user()->id;
			}

			$investigation->update( $updates );
			$investigation->newEvent( 'edit-investigation', false, null, $request->user() );
		} elseif ( $request->input( 'event' ) === 'appeal-recv' ) {
			$newAppeal = Appeal::factory()->create(
				[
					'investigation' => $investigation,
					'type' => $request->input( 'appeal-type' ),
					'text' => $request->input( 'comments' ),
					'assigned' => $request->user(),
				]
			);

			$investigation->newEvent(
				'appeal-recv',
				true,
				'#' . $newAppeal->id,
				$request->user()
			);

			AppealNew::dispatch( $newAppeal );
		} else {
			$investigation->newEvent(
				$request->input( 'event' ),
				!( in_array( $request->input( 'event' ), [ 'comment', 'edit-investigation' ], true ) ),
				$request->input( 'comments' ),
				$request->user()
			);

			$investigation->subject->updateStanding( $request->input( 'event' ) );
			if ( $request->input( 'status' ) !== null ) {
				if ( $investigation->closed ) {
					$investigation->update( [ 'closed' => null ] );
					$investigation->newEvent( 'reopen-investigation', false, $request->input( 'comments' ), $request->user() );
					InvestigationReopened::dispatch( $investigation );
				} else {
					$investigation->update( [ 'closed' => now() ] );
					$investigation->newEvent( 'close-investigation', false, $request->input( 'comments' ), $request->user() );
					InvestigationClosed::dispatch( $investigation );
				}
			}
		}

		$request->session()->flash( 'successFlash', __( 'investigation' ) . ' ' . __( 'toast-updated' ) );
		return redirect( '/investigation/' . $investigation->id );
	}
}
