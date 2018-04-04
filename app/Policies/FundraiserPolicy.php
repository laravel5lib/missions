<?php

namespace App\Policies;

use App\Models\v1\User;
use App\Models\v1\Fundraiser;

class FundraiserPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view the fundraiser.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function view(User $user, Fundraiser $fundraiser)
    {
        if ($fundraiser->sponsor_type === 'groups') {
            return in_array(
                $user->id,
                $fundraiser->sponsor->managers->pluck('id')->toArray()
            );
        }

        return $fundraiser->sponsor_id === $user->id;
    }

    /**
     * Determine whether the user can create funds.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('add_fundraisers');
    }

    /**
     * Determine whether the user can update the fund.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function update(User $user, Fundraiser $fundraiser)
    {
        if ($user->can('edit_fundraisers')) return true;

        if ($fundraiser->sponsor_type === 'groups') {
            return in_array(
                $user->id,
                $fundraiser->sponsor->managers->pluck('id')->toArray()
            );
        }

        return $fundraiser->sponsor_id === $user->id;
    }

    /**
     * Determine whether the user can delete the fund.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->can('delete_fundraisers');
    }
}
