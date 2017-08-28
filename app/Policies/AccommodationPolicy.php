<?php

namespace App\Policies;

use App\Models\v1\User;
use App\App\Models\v1\Accommodation;

class AccommodationPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view the accommodation.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\App\Models\v1\Accommodation  $accommodation
     * @return mixed
     */
    public function view(User $user, Accommodation $accommodation)
    {
        return $user->can('view_accommodations');
    }

    /**
     * Determine whether the user can create accommodations.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('add_accommodations');
    }

    /**
     * Determine whether the user can update the accommodation.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\App\Models\v1\Accommodation  $accommodation
     * @return mixed
     */
    public function update(User $user, Accommodation $accommodation)
    {
        return $user->can('edit_accommodations');
    }

    /**
     * Determine whether the user can delete the accommodation.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\App\Models\v1\Accommodation  $accommodation
     * @return mixed
     */
    public function delete(User $user, Accommodation $accommodation)
    {
        return $user->can('delete_accommodations');
    }
}
