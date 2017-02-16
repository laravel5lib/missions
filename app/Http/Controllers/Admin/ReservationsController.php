<?php

namespace App\Http\Controllers\Admin;

use App\Models\v1\Reservation;
use App\Http\Controllers\Controller;

class ReservationsController extends Controller
{

    /**
     * @var Reservation
     */
    private $reservation;

    /**
     * ReservationsController constructor.
     * @param Reservation $reservation
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Get a list of reservations.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($tab = null)
    {
        $this->authorize('view', $this->reservation);

        return view('admin.reservations.index', compact('tab'));
    }

    /**
     * Get the specified reservation.
     *
     * @param $id
     * @param string $tab
     * @return $this
     */
    public function show($id, $tab = "details")
    {
        $this->authorize('view', $this->reservation);

        $reservation = $this->api->get('reservations/'.$id, ['include' => 'trip.campaign,fundraisers,costs.payments']);

        $rep = $reservation->rep ? $reservation->rep : $reservation->trip->rep;

        return view('admin.reservations.' . $tab, compact('reservation', 'rep', 'tab'));
    }

    /**
     * Edit the specified reservation
     *
     * @param $reservationId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($reservationId)
    {
        $this->authorize('edit', $this->reservation);

        return view('admin.reservations.edit')->with('reservationId', $reservationId);
    }

    /**
     * Create a new reservation.
     */
    public function create()
    {
        $this->authorize('create', $this->reservation);
    }}
