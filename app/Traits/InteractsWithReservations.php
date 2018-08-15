<?php

namespace App\Traits;

use App\Models\v1\Reservation;

trait InteractsWithReservations {

    public function reservations()
    {
        return $this->morphToMany(Reservation::class, 'documentable', 'reservation_documents');
    }

    public function attachToReservation($reservationId, $type = null)
    {
        if ($reservationId) {
            $reservation = Reservation::findOrFail($reservationId);

            $reservation->addDocument($type ?? $this->getMorphClass(), ['document_id' => $this->id]);
        }
    }

    public function detachFromAllReservations($type = null)
    {
        $this->reservations->each(function ($reservation) use ($type) {
            $reservation->removeDocument($type ?? $this->getMorphClass(), $this->id);
        });
    }

    public function detachFromReservation($reservationId, $type = null)
    {
        if ($reservationId) {
            $reservation = Reservation::findOrFail($reservationId);

            $reservation->removeDocument($type ?? $this->getMorphClass(), $this->id);
        }
    }
}