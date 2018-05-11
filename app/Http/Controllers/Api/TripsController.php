<?php

namespace App\Http\Controllers\Api;

use App\Trip;
use App\Jobs\ExportTrips;
use App\Jobs\ApplyGroupPricing;
use App\Http\Controllers\Controller;
use App\Http\Resources\TripResource;
use Dingo\Api\Contract\Http\Request;
use App\Http\Requests\v1\TripRequest;
use App\Http\Requests\v1\ExportRequest;

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

        $trip->update([
            'campaign_id'     => $request->get('campaign_id', $trip->campaign_id),
            'group_id'        => $request->get('group_id', $trip->group_id),
            'country_code'    => $request->get('country_code', $trip->country_code),
            'type'            => $request->get('type', $trip->type),
            'difficulty'      => $request->get('difficulty', $trip->difficulty),
            'started_at'      => $request->get('started_at', $trip->started_at),
            'ended_at'        => $request->get('ended_at', $trip->ended_at),
            'closed_at'       => $request->get('closed_at', $trip->closed_at),
            'rep_id'          => $request->get('rep_id', $trip->rep_id),
            'spots'           => $request->get('spots', $trip->spots),
            'todos'           => $request->get('todos', $trip->todos),
            'prospects'       => $request->get('prospects', $trip->prospects),
            'team_roles'      => $request->get('team_roles', $trip->team_roles),
            'description'     => $request->get('description', $trip->description),
            'published_at'    => $request->has('published_at') ? trim($request->get('published_at')) : $trip->published_at,
            'companion_limit' => $request->get('companion_limit', $trip->companion_limit),
            'public'          => $request->get('public', $trip->public)
        ]);

        $trip->syncFacilitators($request->get('facilitators'));

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
        
        $trip->delete();

        return response()->json([], 204);
    }

    public function checkPromoCode($id, Request $request)
    {
        $trip = Trip::findOrFail($id);

        $reward = $trip->applyCode($request->only('promocode'));

        return $reward ? (string) number_format($reward/100, 2) : abort(422, 'Invalid or expired promo code');
    }

    /**
     * Export trips.
     *
     * @param ExportRequest $request
     * @return mixed
     */
    public function export(ExportRequest $request)
    {
        $this->dispatch(new ExportTrips($request->all()));

        return $this->response()->created(null, [
            'message' => 'Report is being generated and will be available shortly.'
        ]);
    }
}
