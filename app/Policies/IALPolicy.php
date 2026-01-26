<?php

namespace App\Policies;

use App\Models\IAL;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class IALPolicy
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
	 * Determine whether the user can view all IALs.
	 */
	public function viewAny( User $user ): Response
	{
		return Response::deny();
	}

	/**
	 * Determine whether the user can update an IAL.
	 */
	public function update( User $user, IAL $ial ): Response
	{
		return Response::deny();
	}
}
