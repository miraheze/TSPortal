<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
	use HandlesAuthorization;

	/**
	 * Initial auth check
	 */
	public function before( User $user ): ?Response
	{
		if ( $user->hasFlag( 'ts' ) ) {
			return Response::allow();
		}

		return null;
	}

	/**
	 * Determine whether the user can view user list.
	 */
	public function viewAny( User $user ): Response
	{
		return Response::deny( 'no access' );
	}

	/**
	 * Determine whether the user can view the user profile.
	 */
	public function view( User $user, User $model ): Response
	{
		if ( $model->id === $user->id ) {
			return Response::allow();
		} else {
			return Response::deny( 'no access' );
		}
	}

	/**
	 * Determine whether the user can create new users.
	 */
	public function create( User $user ): Response
	{
		return Response::deny( 'no access' );
	}

	/**
	 * Determine whether the user can update a user.
	 */
	public function update( User $user, User $model ): Response
	{
		if ( $user->hasFlag( 'user-manager' ) ) {
			return Response::allow();
		} else {
			return Response::deny();
		}
	}
}
