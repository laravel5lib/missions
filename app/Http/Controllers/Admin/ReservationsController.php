<?php

namespace App\Http\Controllers\Admin;

use App\Models\v1\Reservation;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Traits\SEOTools;

class ReservationsController extends Controller
{
    use SEOTools;

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

        $this->seo()->setTitle('Reservations');

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
        $reservation = $this->api->get('reservations/'.$id, ['include' => 'trip.campaign,fundraisers,costs.payments,squads.team,rooms.type, rooms.accommodations']);

        $this->authorize('view', $reservation);

        $rep = $reservation->rep ? $reservation->rep : $reservation->trip->rep;

        $locked = $reservation->trip->campaign->reservations_locked;

        $title = $reservation->name . '\'s Reservation ' . title_case(str_replace("-", " ", $tab));
        $this->seo()->setTitle($title);

        $pageLinks = [
            'admin/reservations/'.$reservation->id => 'Overview',
            'admin/reservations/'.$reservation->id.'/funding' => 'Funding',
            'admin/reservations/'.$reservation->id.'/requirements' => 'Requirements',
            'admin/reservations/'.$reservation->id.'/costs' => 'Pricing',
            'admin/reservations/'.$reservation->id.'/legal' => 'Legal',
            'admin/reservations/'.$reservation->id.'/resources' => 'Resources'
        ];

        return view('admin.reservations.' . $tab, compact('reservation', 'rep', 'tab', 'locked', 'pageLinks'));
    }

    /**
     * Edit the specified reservation
     *
     * @param $reservationId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($reservationId)
    {
        $this->authorize('update', $this->reservation);

        $this->seo()->setTitle('Edit Reservation');

        return view('admin.reservations.edit')->with('reservationId', $reservationId);
    }

    /**
     * Create a new reservation.
     */
    public function create()
    {
        $this->seo()->setTitle('New Reservation');

        $this->authorize('create', $this->reservation);
    }
}
