<?php

namespace App\Policies;

use App\Models\v1\Fund;
use App\Models\v1\User;
use App\Models\v1\Project;

class FundPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view the fund.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function view(User $user, Fund $fund = null)
    {
        if (! $fund) {
            return $user->can('view_funds');
        }

        // is trip rep
        if ($fund->fundable_type == 'reservations' && $fund->fundable->trip->rep->id == $user->id) {
            return true;
        }

        // is project manager
        if ($fund->fundable_type == 'projects' && $user->can('view', Project::class)) {
            return true;
        }

        // or has permission to view all funds
        return $user->can('view_funds');
    }

    /**
     * Determine whether the user can create funds.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('add_funds');
    }

    /**
     * Determine whether the user can update the fund.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->can('edit_funds');
    }

    /**
     * Determine whether the user can delete the fund.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->can('delete_funds');
    }
}
