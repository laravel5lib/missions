<?php

use App\Models\v1\Trip;
use App\Models\v1\User;
use App\Models\v1\Reservation;

class InProgressReservation extends BaseReservation
{
    /**
     * Makes a reservation with a state of in progress
     * with some requirements completed and
     * with some funds raised.
     *
     * @param array $params Array of params
     *
     * @return Mixed
     */
    public function build($params = [])
    {
        // choose a random trip or create a new one
        $tripId = array_rand(Trip::pluck('id', 'id')->toArray()) ?: (new RandomTrip)->create()->id;

        // choose a random user or create a new one
        $userId = array_rand(User::pluck('id', 'id')->toArray()) ?: (new UserWithEverything)->create()->id;

        $reservation = factory(Reservation::class)->create([
            'trip_id' => $tripId,
            'user_id' => $userId
        ]);

        $this->add_costs($reservation);
        $this->add_requirements($reservation);
        $this->add_deadlines($reservation);
        $this->add_todos($reservation);
        $this->add_fundraiser($reservation);
        $this->make_deposit($reservation);

        // add some funds to the reservation
        $this->add_funds($reservation, $percentStart = 10, $percentEnd = 90);
    }
}
