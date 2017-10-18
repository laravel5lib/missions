<?php

namespace App\Policies;

use App\Models\v1\User;

class RepresentativePolicy extends BasePolicy
{

    /**
     * Determine whether the user can view the representative.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Representative  $representative
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->can('view_representatives');
    }

    /**
     * Determine whether the user can create representatives.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('add_representatives');
    }

    /**
     * Determine whether the user can update the representative.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Representative  $representative
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->can('edit_representatives');
    }

    /**
     * Determine whether the user can delete the representative.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Representative  $representative
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->can('delete_representatives');
    }
}
