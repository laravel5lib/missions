<?php

namespace App\Listeners;

use App\Events\ReservationWasCreated;
use App\Jobs\Reservations\SendFacilitatorNotificationEmail;

class NotifyFacilitatorsOfNewReservation
{
    /**
     * Handle the event.
     *
     * @param  ReservationWasCreated  $event
     * @return void
     */
    public function handle(ReservationWasCreated $event)
    {
        $reservation = $event->reservation;

        dispatch(new SendFacilitatorNotificationEmail($reservation));
    }
}
