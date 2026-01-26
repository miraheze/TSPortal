<?php

namespace App\Policies;

use App\Models\Appeal;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class AppealPolicy
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

		return Response::deny();
	}

	/**
	 * Determine whether the user can view all appeals.
	 */
	public function viewAny( User $user ): Response
	{
		return Response::deny();
	}

	/**
	 * Determine whether the user can view a specific appeal.
	 */
	public function view( User $user, Appeal $appeal ): Response
	{
		return Response::deny();
	}

	/**
	 * Determine whether the user can update a specific appeal.
	 */
	public function update( User $user, Appeal $appeal ): Response
	{
		return Response::deny();
	}
}
