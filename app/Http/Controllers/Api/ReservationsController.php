<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Transformers\v1\DonationTransformer;
use App\Models\v1\Reservation;
use Dingo\Api\Contract\Http\Request;
use App\Http\Requests\v1\ReservationRequest;
use App\Http\Transformers\v1\ReservationTransformer;

class ReservationsController extends Controller
{

    /**
     * @var Reservation
     */
    private $reservation;

    /**
     * Instantiate a new UsersController instance.
     * @param Reservation $reservation
     */
    public function __construct(Reservation $reservation)
    {
        $this->middleware('api.auth', ['only' => 'store', 'update', 'destroy']);
//        $this->middleware('jwt.refresh');
        $this->reservation = $reservation;
    }


    /**
     * Show all reservations.
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $reservations = $this->reservation
                            ->filter($request->all())
                            ->paginate($request->get('per_page', 10));

        return $this->response->paginator($reservations, new ReservationTransformer);
    }

    /**
     * Show the specified reservation.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $reservation = Reservation::findOrFail($id);

        return $this->response->item($reservation, new ReservationTransformer);
    }

    /**
     * Show all the donations for the specified reservation.
     *
     * @param $id
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function donations($id, Request $request)
    {
        $donations = Reservation::findOrFail($id)
                        ->donations()
                        ->paginate($request->get('per_page', 10));

        return $this->response->paginator($donations, new DonationTransformer);
    }

    /**
     * Store a reservation.
     *
     * @param ReservationRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(ReservationRequest $request)
    {
        $reservation = Reservation::create($request->all());

        // Eager load trip relationships for sync
        $reservation->load('trip.costs', 'trip.requirements', 'trip.deadlines');

        if ($request->has('tags'))
            $reservation->tag($request->get('tags'));

        return $this->response->item($reservation, new ReservationTransformer);
    }

    /**
     * Update the specified reservation.
     *
     * @param ReservationRequest $request
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function update(ReservationRequest $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        $reservation->update($request->except('costs', 'requirements', 'deadlines', 'todos'));

        $reservation->syncCosts($request->get('costs'));
        $reservation->syncRequirements($request->get('requirements'));
        $reservation->syncDeadlines($request->get('deadlines'));
        $reservation->syncTodos($request->get('todos'));

        if ($request->has('tags'))
            $reservation->retag($request->get('tags'));

        return $this->response->item($reservation, new ReservationTransformer);
    }

    /**
     * Delete the specified reservation.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);

        $reservation->delete();

        return $this->response->noContent();
    }
}
