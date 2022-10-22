<?php

namespace App\Http\Controllers;

use App\Events\DPANew;
use App\Models\DPA;
use App\Models\User;
use App\Rules\DPAAlreadyLive;
use App\Rules\MirahezeUsernameRule;
use App\Rules\SameAccountRule;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

/**
 * Controller class for DPA request and actions
 */
class DPAController extends Controller
{
	/**
	 * Indexes and shows all DPA requests that are open, filtered for non-privileged users
	 *
	 * @param Request $request
	 *
	 * @return Application|Factory|View
	 */
	public function index( Request $request )
	{
		$allDPAs = DPA::all();

		if ( !$request->user()->hasFlag( 'ts' ) ) {
			$allDPAs = $allDPAs->where( 'user', $request->user()->id )->whereNull( 'underage' );
		}

		return view( 'dpa' )->with( 'dpas', $allDPAs->whereNull( 'completed' ) );
	}

	/**
	 *  Shows a specific DPA request
	 *
	 * @param DPA $dpa
	 *
	 * @return Application|Factory|View
	 */
	public function show( DPA $dpa )
	{
		return view( 'dpa.view' )->with( 'dpa', $dpa );
	}

	/**
	 * Stores a processed new DPA request
	 *
	 * @param DPA $dpa
	 * @param Request $request
	 *
	 * @return Application|RedirectResponse|Redirector
	 */
	public function store( DPA $dpa, Request $request )
	{
		$request->validate(
			[
				'username' => [ new MirahezeUsernameRule, new DPAAlreadyLive ]
			]
		);

		$dpaUser = User::findOrCreate( $request->input( 'username' ) );

		if ( $request->input( 'username-type' ) == 'own-removal' ) {
			$request->validate(
				[
					'username' => [ new SameAccountRule ]
				]
			);

			$dpa::factory()->create(
				[
					'user'      => $dpaUser,
					'statutory' => (bool)$request->input( 'dpa' )
				]
			);
		} else {
			$dpa::factory()->create(
				[
					'user'      => $dpaUser,
					'underage'  => $request->input( 'evidence' ),
					'statutory' => true
				]
			);
		}

		$event = ( count( $dpaUser->events ) == 0 ) ? 'created-dpa' : 'new-dpa';

		$dpaUser->newEvent( $event );

		$newDPA = DPA::query()->orderBy( 'filed', 'DESC' )->limit( 1 )->get()->all()[0];

		DPANew::dispatch( $newDPA );

		request()->session()->flash( 'successFlash', __( 'dpa' ) . ' ' . __( 'toast-submitted' ) );

		return redirect( '/dpa' );
	}

	/**
	 * Shows the form to create a new DPA request
	 *
	 * @return Application|Factory|View
	 */
	public function create()
	{
		return view( 'dpa.new' );
	}

	/**
	 * Processor for updating a request once processed
	 *
	 * @param DPA $dpa
	 * @param Request $request
	 *
	 * @return RedirectResponse
	 */
	public function update( DPA $dpa, Request $request ): RedirectResponse
	{
		if ( $request->input( 'approve' ) ?? false ) {
			$dpa->update( [
				'completed' => now()
			] );

			$dpa->user->update( [
				'username' => 'MirahezeGDPR ' . $dpa->id
			] );
		} else {
			$dpa->update( [
				'completed' => now(),
				'reject'    => $request->input( 'reason' )
			] );
		}

		$dpa->user->newEvent( 'closed-dpa', $request->user() );

		request()->session()->flash( 'successFlash', __( 'dpa' ) . ' ' . __( 'toast-updated' ) );

		return back();
	}
}
