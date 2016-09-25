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
    public function index()
    {
        $this->authorize('view', $this->reservation);

        return view('admin.reservations.index');
    }

    /**
     * Get the specified reservation.
     *
     * @param $id
     * @return $this
     */
    public function show($id)
    {
        $this->authorize('view', $this->reservation);

        $reservation = $this->api->get('reservations/'.$id, ['include' => 'trip.campaign,fundraisers,costs.payments']);

        return view('admin.reservations.show', compact('reservation'));
    }

    /**
     * Edit the specified reservation
     *
     * @param $reservationId
     * @return $this
     */
    public function edit($reservationId)
    {
        $this->authorize('edit', $this->reservation);

        return view('admin.reservations.edit')->with('reservationId', $reservationId);
    }

    /**
     * Create a new reservation.
     *
     * @param $campaignId
     */
    public function create($campaignId)
    {
        $this->authorize('create', $this->reservation);

        // $campaign = Reservation::whereId($campaignId)->orWhere('page_url', $campaignId)->first();
        // $this->api->get('campaigns/'.$campaignId);
        // return view('admin.reservations.create')->with('campaign', $campaign);
    }}
