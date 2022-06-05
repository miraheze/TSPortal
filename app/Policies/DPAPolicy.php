<?php

namespace App\Policies;

use App\Models\DPA;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class DPAPolicy
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
	 * Determine whether the user can view a specific DPA.
	 *
	 * @param User $user
	 * @param DPA $dpa
	 *
	 * @return Response
	 */
	public function view( User $user, DPA $dpa ): Response
	{
		if ( $user->id == $dpa->subject->id ) {
			return Response::allow();
		} else {
			return Response::deny();
		}
	}

	/**
	 * Determine whether the user can create a DPA.
	 *
	 * @param User $user
	 *
	 * @return Response
	 */
	public function create( User $user ): Response
	{
		return Response::allow();
	}

	/**
	 * Determine whether the user can update a DPA.
	 *
	 * @param User $user
	 * @param DPA $dpa
	 *
	 * @return Response
	 */
	public function update( User $user, DPA $dpa ): Response
	{
		return Response::deny();
	}
}
