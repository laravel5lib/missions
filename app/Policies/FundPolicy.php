<?php

namespace App\Policies;

use App\Models\v1\User;
use App\Models\v1\Fund;

class FundPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view the fund.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function view(User $user)
    {
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
