<?php

declare( strict_types = 1 );

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Attributes\Middleware;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

/**
 * Controller for handling user logins and creations.
 */
class LoginController
{
	/**
	 * Callback for OAuth application to handle processing of logins.
	 */
	#[Middleware( 'guest' )]
	public function callback(): RedirectResponse
	{
		$socialiteUser = Socialite::driver( 'mediawiki' )->user();
		$user = User::findOrCreate( $socialiteUser->name, true );

		if ( count( $user->events ) === 0 ) {
			$user->newEvent( 'created-login' );
		}

		abort_if( $user->hasFlag( 'login-disabled' ), 403, __( 'login-disabled' ) );

		Auth::login( $user );
		return redirect()->intended();
	}

	/**
	 * Handles login web requests to forward to OAuth.
	 */
	#[Middleware( 'guest' )]
	public function login(): RedirectResponse
	{
		return Socialite::driver( 'mediawiki' )->redirect();
	}

	/**
	 * Handles a logout.
	 */
	#[Middleware( 'auth' )]
	public function logout( Request $request ): RedirectResponse
	{
		$this->guard()->logout();
		$request->session()->invalidate();
		return redirect( '/' );
	}

	/**
	 * Guards the application for logins.
	 */
	private function guard(): StatefulGuard
	{
		return Auth::guard();
	}
}
