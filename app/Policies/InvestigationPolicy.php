<?php

namespace App\Policies;

use App\Models\Investigation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class InvestigationPolicy
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

		return null;
	}

	/**
	 * Determine whether the user can view all investigations.
	 *
	 * @param User $user
	 *
	 * @return Response
	 */
	public function viewAny( User $user ): Response
	{
		return Response::deny();
	}

	/**
	 * Determine whether the user can view a specific investigation.
	 *
	 * @param User $user
	 * @param Investigation $investigation
	 *
	 * @return Response
	 */
	public function view( User $user, Investigation $investigation ): Response
	{
		return Response::deny();
	}

	/**
	 * Determine whether the user can create an investigation.
	 *
	 * @param User $user
	 *
	 * @return Response
	 */
	public function create( User $user ): Response
	{
		return Response::deny();
	}

	/**
	 * Determine whether the user can update a specific investigation.
	 *
	 * @param User $user
	 * @param Investigation $investigation
	 *
	 * @return Response
	 */
	public function update( User $user, Investigation $investigation ): Response
	{
		return Response::deny();
	}
}
