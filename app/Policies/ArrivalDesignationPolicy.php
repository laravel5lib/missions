<?php

namespace App\Policies;

use App\Models\v1\User;
use App\Models\v1\ArrivalDesignation;

class ArrivalDesignationPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view the arrival designation.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\ArrivalDesignation  $designation
     * @return mixed
     */
    public function view(User $user, ArrivalDesignation $designation)
    {
        // has permission?
        if ($user->can('view_arrival_designations')) {
            return true;
        }

        // is manager, facilitator, or owner?
        return $user->doesManage($designation) ?: $user->id == $designation->reservation->user_id;
    }

    /**
     * Determine whether the user can create arrival designations.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        // admin routes require permission
        if (request()->is('admin/*')) {
            return $user->can('add_arrival_designations');
        }

        return true;
    }

    /**
     * Determine whether the user can update the Arrival Designation.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\ArrivalDesignation  $designation
     * @return mixed
     */
    public function update(User $user, ArrivalDesignation $designation)
    {
        // has permission?
        if ($user->can('edit_arrival_designations')) {
            return true;
        }

        // is manager, facilitator, or owner?
        return $user->doesManage($designation) ?: $user->id == $designation->reservation->user_id;
    }

    /**
     * Determine whether the user can delete the arrival designation.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\ArrivalDesignation  $designation
     * @return mixed
     */
    public function delete(User $user, ArrivalDesignation $designation = null)
    {
        // has permission?
        if ($user->can('delete_arrival_designations')) {
            return true;
        }

        if (! $designation) {
            return false;
        }

        // is manager, facilitator, or owner?
        return $user->doesManage($designation) ?: $user->id == $designation->reservation->user_id;
    }
}
