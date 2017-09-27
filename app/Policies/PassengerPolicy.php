<?php

namespace App\Policies;

use App\Models\v1\User;
use App\App\Models\v1\Passenger;

class PassengerPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view the passenger.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\App\Models\v1\Passenger  $passenger
     * @return mixed
     */
    public function view(User $user, Passenger $passenger)
    {
        return $user->can('view_passengers');
    }

    /**
     * Determine whether the user can create passengers.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('add_passengers');
    }

    /**
     * Determine whether the user can update the passenger.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\App\Models\v1\Passenger  $passenger
     * @return mixed
     */
    public function update(User $user, Passenger $passenger)
    {
        return $user->can('edit_passengers');
    }

    /**
     * Determine whether the user can delete the passenger.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\App\Models\v1\Passenger  $passenger
     * @return mixed
     */
    public function delete(User $user, Passenger $passenger)
    {
        return $user->can('delete_passengers');
    }
}
