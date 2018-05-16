<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\v1\Reservation;
use App\Http\Controllers\Controller;

class ReservationPriceLockController extends Controller
{
    public function store($reservationId)
    {
        $reservation = Reservation::findOrFail($reservationId);

        $price = $reservation->getCurrentRate();

        $price ?? abort(422, 'No price found');

        return $reservation->lockPrice($price);
    }

    public function destroy($reservationId)
    {
        $reservation = Reservation::findOrFail($reservationId);
        
        $price = $reservation->getCurrentRate();

        $price ?? abort(422, 'No price found');

        return $reservation->unlockPrice($price);
    }
}
