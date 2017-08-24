<?php

namespace App\Policies;

use App\Models\v1\User;
use App\Models\v1\Reservation;

class ReservationPolicy extends BasePolicy
{
    /**
     * Determine whether the user can view the reservation.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\Reservation  $reservation
     * @return mixed
     */
    public function view(User $user, Reservation $reservation)
    {
        return $user->can('view_reservations');
    }

    /**
     * Determine whether the user can create reservations.
     *
     * @param  \App\Models\v1\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('add_reservations');
    }

    /**
     * Determine whether the user can update the reservation.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\Reservation  $reservation
     * @return mixed
     */
    public function update(User $user, Reservation $reservation)
    {
        if ($user->can('edit_reservations')) {
            return true;
        }

        // only the owning user can update the reservation
        return $user->id === $reservation->user_id;
    }

    /**
     * Determine whether the user can delete the reservation.
     *
     * @param  \App\Models\v1\User  $user
     * @param  \App\Models\v1\Reservation  $reservation
     * @return mixed
     */
    public function delete(User $user, Reservation $reservation)
    {
        return $user->can('delete_reservations');
    }
}
