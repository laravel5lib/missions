<?php

namespace App\Policies;

use App\Models\v1\User;
use App\Models\v1\Trip;

class TripPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view the trip.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\Trip  $trip
     * @return mixed
     */
    public function view(User $user, Trip $trip)
    {
        return $user->can('view_trips');
    }

    /**
     * Determine whether the user can create trips.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('add_trips');
    }

    /**
     * Determine whether the user can update the trip.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\Trip  $trip
     * @return mixed
     */
    public function update(User $user, Trip $trip)
    {
        return $user->can('edit_trips');
    }

    /**
     * Determine whether the user can delete the trip.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\Trip  $trip
     * @return mixed
     */
    public function delete(User $user, Trip $trip)
    {
        return $user->can('delete_trips');
    }
}
