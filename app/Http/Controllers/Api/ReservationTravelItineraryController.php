<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ReservationTravelItineraryController extends Controller
{
    function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function show($id)
    {
        $reservation = $this->reservation->findOrFail($id);

        // $reservation->itineraries->travel()->with('items')->first();
    }
}
