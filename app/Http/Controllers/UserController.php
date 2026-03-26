<?php

declare( strict_types = 1 );

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use function back;
use function view;

/**
 * Controller for all user related actions outside a login.
 */
class UserController
{
	/**
	 * Indexes and shows a list of all users.
	 */
	public function index(): View
	{
		$query = User::paginate( 500 );
		return view( 'users' )->with( 'users', $query );
	}

	/**
	 * Show a specific user page.
	 */
	public function show( User $user ): View
	{
		return view( 'user.view' )->with( 'user', $user );
	}

	/**
	 * Update a users flags.
	 */
	public function update( Request $request, User $user ): RedirectResponse
	{
		$user->updateFlags( $request->input( 'new-access' ) ?? [], $request->user() );
		return back();
	}
}
