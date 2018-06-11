<?php

namespace App\Http\Controllers\Api;

use App\Models\v1\Flight;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\Filter;
use App\Http\Controllers\Controller;
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
    public function store(Request $request)
    {   
        Flight::create($request->all());

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
    public function update(Request $request, $uuid)
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
