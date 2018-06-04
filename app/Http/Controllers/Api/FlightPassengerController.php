<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\v1\Reservation;
use App\Http\Controllers\Controller;
use App\Http\Resources\FlightPassengerResource;

class FlightPassengerController extends Controller
{
    public function index($campaignId)
    {
        $passengers = Reservation::whereHas('trip', function ($trip) use ($campaignId) {
            return $trip->where('campaign_id', $campaignId);
        })
        ->whereNotNull('flight_itinerary_id')
        ->whereHas('flightItinerary.flights', function($flight) {
            return $flight->segment(request()->input('filter.segment'));
        })
        ->with(['flightItinerary.flights' => function($flight) {
            $flight->segment(request()->input('filter.segment'));
        }, 'trip.group'])
        ->paginate(25);

        return FlightPassengerResource::collection($passengers);
    }
}
