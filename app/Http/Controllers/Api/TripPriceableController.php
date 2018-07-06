<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\v1\Trip;
use Illuminate\Http\Request;

class TripPriceableController extends Controller
{
    public function store($tripId, $priceId)
    {
        $trip = Trip::findOrFail($tripId);
        
        $trip->reservations->each(function ($reservation) use ($priceId) {
            $reservation->addPrice(['price_id' => $priceId]);
        });

        return response()->json(['Price added to reservations.'], 201);
    }
}
