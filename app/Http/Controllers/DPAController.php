<?php

declare( strict_types = 1 );

namespace App\Http\Controllers;

use App\Events\DPANew;
use App\Models\DPA;
use App\Models\User;
use App\Rules\DPAAlreadyLive;
use App\Rules\MirahezeUsernameRule;
use App\Rules\SameAccountRule;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Controller class for DPA request and actions.
 */
class DPAController
{
	/**
	 * Indexes and shows all DPA requests that are open, filtered for non-privileged users.
	 */
	public function index( Request $request ): View
	{
		$query = DPA::query()->whereNull( 'completed' )->oldest( 'filed' );
		if ( !$request->user()->hasFlag( 'ts' ) ) {
			$query->where( 'user', $request->user()->id )->whereNull( 'underage' );
		}

		return view( 'dpa' )->with( 'dpas', $query->get() );
	}

	/**
	 * Shows a specific DPA request.
	 */
	public function show( DPA $dpa ): View
	{
		return view( 'dpa.view' )->with( 'dpa', $dpa );
	}

	/**
	 * Stores a processed new DPA request.
	 */
	public function store( DPA $dpa, Request $request ): RedirectResponse
	{
		$request->validate(
			[
				'username' => [ new MirahezeUsernameRule, new DPAAlreadyLive ],
			]
		);

		$dpaUser = User::findOrCreate( $request->input( 'username' ) );
		if ( $request->input( 'username-type' ) === 'own-removal' ) {
			$request->validate(
				[
					'username' => [ new SameAccountRule ],
				]
			);

			$dpa::factory()->create(
				[
					'user' => $dpaUser,
					'statutory' => (bool)$request->input( 'dpa' ),
				]
			);
		} else {
			$request->validate(
				[
					'evidence' => [ 'required', 'string' ],
				]
			);

			$dpa::factory()->create(
				[
					'user' => $dpaUser,
					'underage' => $request->input( 'evidence' ),
					'statutory' => true,
				]
			);
		}

		$event = $dpaUser->events()->exists() ? 'new-dpa' : 'created-dpa';
		$dpaUser->newEvent( $event );

		$newDPA = DPA::query()->latest( 'filed' )->first();
		DPANew::dispatch( $newDPA );

		$request->session()->flash( 'successFlash', __( 'dpa' ) . ' ' . __( 'toast-submitted' ) );
		return redirect( '/dpa' );
	}

	/**
	 * Shows the form to create a new DPA request.
	 */
	public function create(): View
	{
		return view( 'dpa.new' );
	}

	/**
	 * Processor for updating a request once processed.
	 */
	public function update( DPA $dpa, Request $request ): RedirectResponse
	{
		if ( $request->input( 'approve' ) ?? false ) {
			$dpa->update( [ 'completed' => now() ] );
			$dpa->user->update( [
				'username' => 'MirahezeGDPR ' . $dpa->id,
			] );
		} else {
			$dpa->update( [
				'completed' => now(),
				'reject' => $request->input( 'reason' ),
			] );
		}

		$dpa->user->newEvent( 'closed-dpa', $request->user() );
		$request->session()->flash( 'successFlash', __( 'dpa' ) . ' ' . __( 'toast-updated' ) );

		return back();
	}
}
