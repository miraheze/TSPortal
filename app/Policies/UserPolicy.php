<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserPolicy {
	use HandlesAuthorization;

	/**
	 * Initial auth check
	 *
	 * @param User $user
	 *
	 * @return Response|null
	 */
	public function before( User $user ): ?Response {
		if ( $user->hasFlag( 'ts' ) ) {
			return Response::allow();
		}

		return null;
	}

	/**
	 * Determine whether the user can view user list.
	 *
	 * @param User $user
	 *
	 * @return Response
	 */
	public function viewAny( User $user ): Response {
		return Response::deny( 'no access' );
	}

	/**
	 * Determine whether the user can view the user profile.
	 *
	 * @param User $user
	 * @param User $model
	 *
	 * @return Response
	 */
	public function view( User $user, User $model ): Response {
		if ( $model->id === $user->id ) {
			return Response::allow();
		} else {
			return Response::deny( 'no access' );
		}
	}

	/**
	 * Determine whether the user can create new users.
	 *
	 * @param User $user
	 *
	 * @return Response
	 */
	public function create( User $user ): Response {
		return Response::deny( 'no access' );
	}

	/**
	 * Determine whether the user can update a user.
	 *
	 * @param User $user
	 * @param User $model
	 *
	 * @return Response
	 */
	public function update( User $user, User $model ): Response {
		if ( $user->hasFlag( 'user-manager' ) ) {
			return Response::allow();
		} else {
			return Response::deny();
		}
	}
}
