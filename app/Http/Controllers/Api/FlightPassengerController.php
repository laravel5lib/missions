<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\v1\Reservation;
use Spatie\QueryBuilder\Filter;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\FlightPassengerResource;

class FlightPassengerController extends Controller
{
    public function index($campaignId)
    {
        $segment = request()->input('segment');

        $query = Reservation::whereHas('trip', function ($trip) use ($campaignId) {
            return $trip->where('campaign_id', $campaignId);
        })
        ->whereNotNull('flight_itinerary_id')
        ->when($segment, function ($query) use ($segment) {
            $query->whereHas('flightItinerary.flights', function($flight) use ($segment) {
                return $flight->segment($segment);
            })->with(['flightItinerary.flights' => function($flight) use ($segment) {
                $flight->segment($segment);
            }, 'trip.group']);
        });


        $passengers = QueryBuilder::for($query)
            ->allowedFilters([
                'surname', 
                'given_names', 
                Filter::scope('record_locator'),
                Filter::scope('flight_no'),
                Filter::scope('iata_code'),
                Filter::scope('group'),
                Filter::scope('trip_type'),
                Filter::scope('itinerary')
            ])
            ->allowedIncludes('flight-itinerary')
            ->paginate(25);

        return FlightPassengerResource::collection($passengers);
    }

    public function update($campaignId, Request $request)
    {
        foreach($request->input('reservations') as $id)
        {
            DB::table('reservations')
                ->where('id', $id)
                ->update(['flight_itinerary_id' => null]);
        }
    }
}
