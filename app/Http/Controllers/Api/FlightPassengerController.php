<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\v1\Reservation;
use Spatie\QueryBuilder\Filter;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\FlightPassengerResource;

class FlightPassengerController extends Controller
{
    public function index($campaignId)
    {
        $query = Reservation::whereHas('trip', function ($trip) use ($campaignId) {
            return $trip->where('campaign_id', $campaignId);
        })
        ->whereNotNull('flight_itinerary_id')
        ->whereHas('flightItinerary.flights', function($flight) {
            return $flight->segment(request()->input('segment'));
        })
        ->with(['flightItinerary.flights' => function($flight) {
            $flight->segment(request()->input('segment'));
        }, 'trip.group']);


        $passengers = QueryBuilder::for($query)
            ->allowedFilters([
                'surname', 
                'given_names', 
                Filter::scope('record_locator'),
                Filter::scope('flight_no'),
                Filter::scope('iata_code')
            ])
            ->paginate(25);

        return FlightPassengerResource::collection($passengers);
    }
}
