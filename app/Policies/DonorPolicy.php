<?php

namespace App\Policies;

use App\Models\v1\User;
use App\Models\v1\Donor;

class DonorPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view the donor.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->can('view_donors');
    }

    /**
     * Determine whether the user can create donors.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('add_donors');
    }

    /**
     * Determine whether the user can update the donor.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\Donor  $donor
     * @return mixed
     */
    public function update(User $user, Donor $donor)
    {
        return $user->can('edit_donors');
    }

    /**
     * Determine whether the user can delete the donor.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\Donor  $donor
     * @return mixed
     */
    public function delete(User $user, Donor $donor)
    {
        return $user->can('delete_donors');
    }
}
