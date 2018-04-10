<?php

namespace App\Http\Controllers\Api;

use App\Models\v1\Trip;
use App\Jobs\ExportTrips;
use App\Http\Controllers\Controller;
use Dingo\Api\Contract\Http\Request;
use App\Http\Requests\v1\TripRequest;
use App\Http\Requests\v1\ExportRequest;
use App\Http\Requests\v1\ImportRequest;
use App\Services\Importers\TripListImport;
use App\Http\Transformers\v1\TripTransformer;

class TripsController extends Controller
{

    /**
     * @var Trip
     */
    private $trip;

    /**
     * Instantiate a new TripsController instance.
     * @param Trip $trip
     */
    public function __construct(Trip $trip)
    {
        $this->trip = $trip;
    }

    /**
     * Get a list of trips
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $trips = $this->trip
                      ->withCount('reservations')
                      ->filter($request->all())
                      ->paginate($request->get('per_page', 10));

        return $this->response->paginator($trips, new TripTransformer);
    }

    /**
     * Get a single trip
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $trip = $this->trip->withCount('reservations')->findOrFail($id);

        return $this->response->item($trip, new TripTransformer);
    }

    /**
     * Create a new trip and save in storage
     *
     * @param TripRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(TripRequest $request)
    {
        $trip = $this->trip->create($request->all());

        return $this->response->item($trip, new TripTransformer);
    }

    /**
     * Duplicate a trip
     *
     * @param  TripRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function duplicate(TripRequest $request)
    {
        // $trip = $this->trip->create($request->all());

        // $trip->syncDeadlines($request->get('deadlines'));
        // $trip->syncRequirements($request->get('requirements'));
        // // $trip->syncCosts($request->get('costs'));

        // return $this->response->item($trip, new TripTransformer);
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
        $trip = $this->trip->findOrFail($id);

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

        if ($request->has('tags')) {
            $trip->retag($request->get('tags'));
        }

        $trip->syncFacilitators($request->get('facilitators'));

        return $this->response->item($trip, new TripTransformer);
    }

    /**
     * Delete a trip
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        $trip = Trip::findOrFail($id);
        
        $trip->delete();

        return $this->response->noContent();
    }

    public function checkPromoCode($id, Request $request)
    {
        $trip = $this->trip->findOrFail($id);

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

    /**
     * Import a list of trips.
     *
     * @param  TripListImport $import
     * @return response
     */
    public function import(ImportRequest $request, TripListImport $import)
    {
        $response = $import->handleImport();

        return $this->response()->created(null, $response);
    }
}
