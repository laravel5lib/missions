<?php

namespace App\Http\Controllers\Api;

use App\Models\v1\Flight;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\Filter;
use App\Models\v1\FlightSegment;
use App\Models\v1\FlightItinerary;
use App\Http\Controllers\Controller;
use App\Http\Requests\FlightRequest;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\FlightResource;

class FlightController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $flights = QueryBuilder::for(Flight::class)
            ->allowedFilters([
                'flight_no',
                'iata_code',
                Filter::scope('segment'),
                Filter::scope('itinerary'),
                Filter::scope('record_locator')
            ])
            ->allowedIncludes(['flight-itinerary', 'flight-segment'])
            ->paginate();

        return FlightResource::collection($flights);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FlightRequest $request)
    {   
        $segment = FlightSegment::whereUuid($request->input('flight_segment_id'))->firstOrFail();
        $itinerary = FlightItinerary::whereUuid($request->input('flight_itinerary_id'))->firstOrFail();

        Flight::create([
            'flight_segment_id' => $segment->id,
            'flight_itinerary_id' => $itinerary->id,
            'flight_no' => $request->input('flight_no'),
            'date' => $request->input('date'),
            'time' => $request->input('time'),
            'iata_code' => $request->input('iata_code')
        ]);

        return response()->json(['message' => 'New flight created.'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $flight = Flight::whereUuid($uuid)->firstOrFail();

        return new FlightResource($flight);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function update(FlightRequest $request, $uuid)
    {
        $flight = Flight::whereUuid($uuid)->firstOrFail();

        $flight->update([
            'flight_no' => $request->input('flight_no', $flight->flight_no),
            'date' => $request->input('date', $flight->date),
            'time' => $request->input('time', $flight->time),
            'iata_code' => $request->input('iata_code', $flight->iata_code),
        ]);

        return new FlightResource($flight);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($uuid)
    {
        $flight = Flight::whereUuid($uuid)->firstOrFail();

        $flight->delete();

        return response()->json(['Flight deleted.'], 204);
    }
}
