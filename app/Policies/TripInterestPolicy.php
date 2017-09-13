<?php

namespace App\Policies;

use App\Models\v1\User;
use App\Models\v1\TripInterest;

class TripInterestPolicy extends BasePolicy
{

    /**
     * Determine whether the user can view the tripInterest.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\TripInterest  $tripInterest
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->can('view_trip_interests');
    }

    /**
     * Determine whether the user can create tripInterests.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('add_trip_interests');
    }

    /**
     * Determine whether the user can update the tripInterest.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\TripInterest  $tripInterest
     * @return mixed
     */
    public function update(User $user, TripInterest $tripInterest = null)
    {
        if ($user->can('edit_trip_interests')) {
            return true;
        }

        return $tripInterest ? $tripInterest->trip->rep_id == $user->id : false;
    }

    /**
     * Determine whether the user can delete the tripInterest.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\TripInterest  $tripInterest
     * @return mixed
     */
    public function delete(User $user, TripInterest $tripInterest = null)
    {
        if ($user->can('delete_trip_interests')) {
            return true;
        }

        return $tripInterest ? $tripInterest->trip->rep_id == $user->id : false;
    }
}
