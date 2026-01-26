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
     */
    public function before(User $user): ?Response
    {
        if ($user->hasFlag('ts')) {
            return Response::allow();
        }

        return null;
    }

    /**
     * Determine whether the user can view a specific DPA.
     */
    public function view(User $user, DPA $dpa): Response
    {
        if ($user->id == $dpa->subject->id) {
            return Response::allow();
        } else {
            return Response::deny();
        }
    }

    /**
     * Determine whether the user can create a DPA.
     */
    public function create(User $user): Response
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can update a DPA.
     */
    public function update(User $user, DPA $dpa): Response
    {
        return Response::deny();
    }
}
