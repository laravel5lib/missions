<?php

namespace App\Http\Controllers\api;

use App\Models\v1\Trip;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\UpdateReservationTodos;

class TripTodosController extends Controller
{

    /**
     * @var Trip
     */
    private $trip;

    /**
     * TripTodosController constructor.
     * @param Trip $trip
     */
    public function __construct(Trip $trip)
    {
        $this->trip = $trip;
    }

    /**
     * Get todos.
     *
     * @param $tripId
     * @return array
     */
    public function index($tripId)
    {
        $trip = $this->trip->findOrFail($tripId);

        return ['data' => $trip->todos];
    }

    /**
     * Save todos.
     *
     * @param Request $request
     * @param $tripId
     * @return array
     */
    public function store(Request $request, $tripId)
    {
        $this->validate($request, [
            'todos' => 'required|array'
        ]);

        $trip = $this->trip->findOrFail($tripId);

        $trip->todos = $request->get('todos');
        $trip->save();

        $this->dispatch(new UpdateReservationTodos($trip));

        return ['data' => $trip->todos];
    }
}
