<?php

declare( strict_types = 1 );

namespace App\Http\Controllers;

use App\Events\DPANew;
use App\Models\DPA;
use App\Models\User;
use App\Rules\DPAAlreadyLive;
use App\Rules\MediaWikiUsernameRule;
use App\Rules\SameAccountRule;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use function back;
use function config;
use function now;
use function redirect;
use function view;

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
		$query = DPA::whereNull( 'completed' )->oldest( 'filed' );
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
		$request->validate( [
			'username' => [ new MediaWikiUsernameRule, new DPAAlreadyLive ],
		] );

		$dpaUser = User::findOrCreate( $request->input( 'username' ) );
		if ( $request->input( 'username-type' ) === 'own-removal' ) {
			$request->validate( [
				'username' => [ new SameAccountRule ],
			] );

			$dpa::factory()->create( [
				'user' => $dpaUser,
				'statutory' => $request->boolean( 'dpa' ),
			] );
		} else {
			$request->validate( [
				'evidence' => [ 'required', 'string' ],
			] );

			$dpa::factory()->create( [
				'user' => $dpaUser,
				'underage' => $request->input( 'evidence' ),
				'statutory' => true,
			] );
		}

		$event = $dpaUser->events()->exists() ? 'new-dpa' : 'created-dpa';
		$dpaUser->newEvent( $event );

		$newDPA = DPA::latest( 'filed' )->first();
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
		if ( $request->boolean( 'approve' ) ) {
			$response = Http::get( config( 'app.urls.mediawiki.api' ), [
				'format' => 'json',
				'action' => 'query',
				'meta' => 'globaluserinfo',
				'guiuser' => $dpa->user->username,
			] );

			if ( isset( $response['query']['globaluserinfo']['id'] ) ) {
				$request->session()->flash( 'errorFlash', __( 'username-still-exists' ) );
				return back();
			}

			$dpa->update( [ 'completed' => now() ] );
			$dpa->user->update( [
				'username' => 'MirahezeGDPR ' . $dpa->id,
			] );

			$dpa->user->newEvent( 'closed-dpa', actor: $request->user() );
		} else {
			$dpa->update( [
				'completed' => now(),
				'reject' => $request->input( 'reason' ),
			] );

			$dpa->user->newEvent( 'closed-dpa', $request->input( 'reason' ), $request->user() );
		}

		$request->session()->flash( 'successFlash', __( 'dpa' ) . ' ' . __( 'toast-updated' ) );
		return back();
	}
}
