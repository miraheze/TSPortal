<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class AppealPolicy
{
	use HandlesAuthorization;

	/**
	 * Initial auth check
	 *
	 * @param User $user
	 *
	 * @return Response|null
	 */
	public function before( User $user ): ?Response
	{
		if ( $user->hasFlag( 'ts' ) ) {
			return Response::allow();
		}

		return Response::deny();
	}
}
