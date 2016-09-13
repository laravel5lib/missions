<?php

namespace App\Http\Controllers\Api;

use App\Events\RegisteredForTrip;
use App\Events\ReservationWasCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\TripRegistrationRequest;
use App\Http\Transformers\v1\ReservationTransformer;
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
        $this->middleware('internal', ['only' => ['store', 'update', 'destroy']]);
        $this->middleware('api.auth', ['only' => ['store', 'update', 'destroy']]);
//        $this->middleware('jwt.refresh', ['only' => ['store', 'update', 'destroy']]);
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
        $trip = $this->trip->create($request->except('deadlines', 'requirements', 'costs'));

        $this->syncResources($trip, $request);

        if ($request->has('tags'))
            $trip->tag($request->get('tags'));

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

        $trip->update($request->except('deadlines', 'requirements', 'costs'));

        $this->syncResources($trip, $request);

        if ($request->has('tags'))
            $trip->retag($request->get('tags'));

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

    private function syncResources(Trip $trip, TripRequest $request)
    {
        $trip->syncDeadlines($request->get('deadlines'));
        $trip->syncCosts($request->get('costs'));
        $trip->syncRequirements($request->get('requirements'));
        $trip->syncFacilitators($request->get('facilitators'));

        if ($request->has('tags'))
            $trip->retag($request->get('tags'));
    }

    public function register(TripRegistrationRequest $request)
    {
        return $request->all();

        $reservation = $this->trip->reservation()
            ->create($request->except('costs', 'donor', 'payment'));

        event(new RegisteredForTrip($reservation, $request));

        return $this->response->item($reservation, new ReservationTransformer);
    }
}
