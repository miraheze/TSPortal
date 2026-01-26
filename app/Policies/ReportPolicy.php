<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Report;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ReportPolicy
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
     * Determine whether the user can view a specific report.
     */
    public function view(User $user, Report $report): Response
    {
        if ($user === $report->reporter) {
            return Response::allow();
        }

        return Response::deny('no access');

    }

    /**
     * Determine whether the user can create a report.
     */
    public function create(User $user): Response
    {
        return Response::allow();
    }

    /**
     * Determine whether the user can update a specific report.
     */
    public function update(User $user, Report $report): Response
    {
        if ($user === $report->reporter) {
            return Response::allow();
        }

        return Response::deny('no access');

    }
}
