<?php

namespace App\Traits;

use App\Models\v1\Reservation;

trait InteractsWithReservations {

    public function reservations()
    {
        return $this->morphToMany(Reservation::class, 'documentable', 'reservation_documents');
    }

    public function attachToReservation($reservationId)
    {
        if ($reservationId) {
            $reservation = Reservation::findOrFail($reservationId);

            $reservation->addDocument($this->getMorphClass(), ['document_id' => $this->id]);
        }
    }

    public function detachFromAllReservations()
    {
        $this->reservations->each(function ($reservation) {
            $reservation->removeDocument($this->getMorphClass(), $this->id);
        });
    }

    public function detachFromReservation($reservationId)
    {
        if ($reservationId) {
            $reservation = Reservation::findOrFail($reservationId);

            $reservation->removeDocument($this->getMorphClass(), $this->id);
        }
    }
}