<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Spatie\QueryBuilder\Filter;
use App\Models\v1\FlightSegment;
use App\Models\v1\FlightItinerary;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\FlightItineraryResource;
use App\Http\Requests\CreateFlightItineraryRequest;

class FlightItineraryController extends Controller
{
    public function index($campaignId)
    {
        $query = FlightItinerary::forCampaign($campaignId);
        
        $itineraries = QueryBuilder::for($query)
            ->allowedFilters([
                'record_locator', 
                Filter::exact('published'), 
                Filter::exact('type')
            ])
            ->allowedIncludes('flights.flight-segment')
            ->withCount(['reservations', 'flights'])
            ->paginate(25);

        return FlightItineraryResource::collection($itineraries);
    }

    public function store($campaignId, CreateFlightItineraryRequest $request)
    {
        DB::transaction(function () use ($request) {
            $itinerary = FlightItinerary::firstOrCreate([
                'record_locator' => $request->input('record_locator'),
                'type' => $request->input('type')
            ]);
            
            // add itineraries to reservations
            DB::table('reservations')
                ->whereIn('id', $request->input('reservations'))
                ->update(['flight_itinerary_id' => $itinerary->id]);

            // create flights
            foreach($request->input('flights') as $flight) {
                $itinerary->flights()->firstOrCreate([
                    'flight_segment_id' => FlightSegment::whereUuid($flight['flight_segment_id'])->firstOrFail()->id,
                    'flight_no' => $flight['flight_no'],
                    'date' => $flight['date'],
                    'time' => $flight['time'],
                    'iata_code' => $flight['iata_code']
                ]);
            }
        });

        return response()->json(['message' => 'Flight itinerary created.'], 201);
    }

    public function update($campaignId, $itineraryId, Request $request)
    {
        $itinerary = FlightItinerary::whereUuid($itineraryId)->firstOrFail();

        $itinerary->update([
            'record_locator' => $request->input('record_locator', $itinerary->record_locator),
            'type' => $request->input('type', $itinerary->type)
        ]);

        return new FlightItineraryResource($itinerary);
    }

    public function destroy($campaignId, $itineraryId)
    {
        $itinerary = FlightItinerary::whereUuid($itineraryId)->firstOrFail();
        
        DB::transaction(function () use ($itinerary) {
            $itinerary->flights()->delete();
            $itinerary->reservations->each(function ($reservation) {
                $reservation->flight_itinerary_id = null;
                $reservation->save();
            });
            $itinerary->delete();
        });

        return response()->json(['message' => 'Itinerary deleted.'], 204);
    }
}
