<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Models\v1\Reservation;
use App\Http\Controllers\Controller;

class ReservationTransfersController extends Controller
{
    /**
     * Create a new transfer
     *
     * @return Response
     */
    public function store($id)
    {
        $reservation = Reservation::findOrFail($id);

        $reservation->transfer()->toTrip(
            request()->get('trip_id'),
            [
                'desired_role' => request()->get('desired_role'), 
                'room_price_id' => request()->get('room_price_id')
            ]
        );

        return response()->json(['message' => 'Transfer complete.'], 201);
    }
}
