<?php

namespace App\Http\Controllers\Api;

use App\Events\ReservationWasCreated;
use App\Http\Controllers\Controller;
use App\Http\Transformers\v1\DonorTransformer;
use App\Models\v1\Donor;
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
        $reservation = $this->reservation->findOrFail($id);

        return $this->response->item($reservation, new ReservationTransformer);
    }

    /**
     * Show all the donors for the specified reservation.
     *
     * @param $id
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
//    public function donors($id, Request $request)
//    {
//        // Filter donors by reservation
//        $request->merge(['reservation' => $id]);
//
//        $donors = Donor::filter($request->all())
//            ->paginate($request->get('per_page'));
//
//        // Pass the reservation to the transformer to filter
//        // embedded relationships by designation.
//        return $this->response->paginator($donors, new DonorTransformer(['reservation' => $id]));
//    }

    /**
     * Store a reservation.
     *
     * @param ReservationRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(ReservationRequest $request)
    {
        $reservation = $this->reservation->create($request->all());

        // Charge and capture deposit

        event(new ReservationWasCreated($reservation, $request));

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
        $reservation = $this->reservation->findOrFail($id);

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
        $reservation = $this->reseration->findOrFail($id);

        $reservation->delete();

        return $this->response->noContent();
    }
}
