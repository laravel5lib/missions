<?php

namespace App\Listeners;

use App\Events\ReservationWasProcessed;
use App\Jobs\Reservations\SendFacilitatorNotificationEmail;

class NotifyFacilitatorsOfNewReservation
{
    /**
     * Handle the event.
     *
     * @param  ReservationWasProcessed  $event
     * @return void
     */
    public function handle(ReservationWasProcessed $event)
    {
        $reservation = $event->reservation;

        dispatch(new SendFacilitatorNotificationEmail($reservation));
    }
}
