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
     */
    public function before(User $user): ?Response
    {
        if ($user->hasFlag('ts')) {
            return Response::allow();
        }

        return null;
    }

    /**
     * Determine whether the user can view all investigations.
     */
    public function viewAny(User $user): Response
    {
        return Response::deny();
    }

    /**
     * Determine whether the user can view a specific investigation.
     */
    public function view(User $user, Investigation $investigation): Response
    {
        return Response::deny();
    }

    /**
     * Determine whether the user can create an investigation.
     */
    public function create(User $user): Response
    {
        return Response::deny();
    }

    /**
     * Determine whether the user can update a specific investigation.
     */
    public function update(User $user, Investigation $investigation): Response
    {
        return Response::deny();
    }
}
