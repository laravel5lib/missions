<?php

namespace App\Http\Controllers\Api;

use App\Trip;
use App\Jobs\ApplyGroupPricing;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\TripResource;
use App\Jobs\ApplyGroupRequirements;
use Dingo\Api\Contract\Http\Request;
use App\Http\Requests\v1\TripRequest;

class TripsController extends Controller
{
    /**
     * Get all trips
     *
     * @return response
     */
    public function index()
    {
        $trips = Trip::getAll()->paginate(10);

        return TripResource::collection($trips);
    }

    /**
     * Get specific trip
     *
     * @param string $id
     * @return response
     */
    public function show($id)
    {
        return new TripResource(Trip::getById($id));
    }

    /**
     * Create a new trip and save in storage
     *
     * @param TripRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(TripRequest $request)
    {
        $trip = Trip::create($request->all());

        if ($request->input('default_prices')) {
            ApplyGroupPricing::dispatch($trip);
        }

        if ($request->input('default_requirements')) {
            ApplyGroupRequirements::dispatch($trip);
        }

        if ($request->input('tags')) {
            $trip->syncTagsWithType($request->input('tags'), 'trip');
        }

        return response()->json(['message' => 'New trip created.'], 201);
    }

    /**
     * Update a trip in storage
     *
     * @param TripRequest $request
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function update(TripRequest $request, $id)
    {
        $trip = Trip::getById($id);

        $trip->update($request->all());

        if ($request->input('tags')) {
            $trip->syncTagsWithType($request->input('tags'), 'trip');
            $trip = $trip->fresh('tags');
        }

        return new TripResource($trip);
    }

    /**
     * Delete a trip
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        $trip = Trip::getById($id);
        
        DB::transaction(function () use ($trip) {
            $trip->reservations()->delete();
            $trip->delete();
        });

        return response()->json([], 204);
    }

    public function checkPromoCode($id, Request $request)
    {
        $trip = Trip::findOrFail($id);

        $reward = $trip->applyCode($request->only('promocode'));

        return $reward ? (string) number_format($reward/100, 2) : abort(422, 'Invalid or expired promo code');
    }
}
