<?php

namespace App\Traits;

use App\Models\v1\Reservation;

trait InteractsWithReservations {

    public function attachToReservation($reservationId)
    {
        if ($reservationId) {
            $reservation = Reservation::findOrFail($reservationId);

            $reservation->addDocument($this->getMorphClass(), ['document_id' => $this->id]);
        }
    }

}