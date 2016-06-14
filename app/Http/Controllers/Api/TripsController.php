<?php

namespace App\Http\Controllers\v1;

use App\Http\Requests;
use App\Models\v1\Trip;
use Dingo\Api\Contract\Http\Request;
use App\Http\Requests\v1\TripRequest;
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
        $this->middleware('api.auth');
//        $this->middleware('jwt.refresh');
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
        $trip = $this->trip->findOrFail($id);

        if ($this->auth->user()->cannot('show', $trip)) {
            abort(403);
        }

        $this->authorize($trip);

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
        $trip = $this->trip->create($request->except('deadlines', 'requirements', 'costs'));

        if ($this->auth->user()->cannot('store', $trip)) {
            abort(403);
        }

        // Syncronize resources
        $trip->syncDeadlines($request->get('deadlines'));
        $trip->syncCosts($request->get('costs'));
        $trip->syncRequirements($request->get('requirements'));

        return $this->response->item($trip, new TripTransformer);
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

        if ($this->auth->user()->cannot('update', $trip)) {
            abort(403);
        }

        $trip->update($request->except('deadlines', 'requirements', 'costs'));

        // Syncronize resources
        $trip->syncDeadlines($request->get('deadlines'));
        $trip->syncCosts($request->get('costs'));
        $trip->syncRequirements($request->get('requirements'));

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

        if ($this->auth->user()->cannot('destroy', $trip)) {
            abort(403);
        }

        $trip->delete();

        return $this->response->noContent();
    }
}
