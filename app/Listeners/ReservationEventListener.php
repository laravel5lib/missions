<?php

namespace App\Listeners;

use App\Jobs\Reservations\ProcessReservation;
use App\Jobs\Reservations\SetupFunding;

class ReservationEventListener {

    /**
     * Handle reservation created events.
     * @param $event
     */
    public function onReservationCreated($event) {

        dispatch(new ProcessReservation($event->reservation, $event->request));

        dispatch(new SetupFunding($event->reservation));

        // close a spot
        $event->reservation->trip->updateSpots(-1);
    }

    /**
     * Handle reservation updated events.
     * @param $event
     */
    public function onReservationUpdated($event) {}

    /**
     * Handle reservation dropped events.
     * @param $event
     */
    public function onReservationDropped($event) {}

    /**
     * Register the listeners for the subscriber.
     *
     * @param  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\ReservationWasCreated',
            'App\Listeners\ReservationEventListener@onReservationCreated'
        );

        $events->listen(
            'App\Events\ReservationWasDropped',
            'App\Listeners\ReservationEventListener@onReservationDropped'
        );
    }

}