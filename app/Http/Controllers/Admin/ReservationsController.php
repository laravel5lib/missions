<?php

namespace App\Http\Controllers\Admin;

use App\Models\v1\Trip;
use App\Models\v1\Campaign;
use App\Models\v1\Reservation;
use App\Models\v1\CampaignGroup;
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
    public function index($campaignId)
    {
        $this->authorize('view', $this->reservation);

        $this->seo()->setTitle('Reservations');

        $campaign = Campaign::findOrFail($campaignId);

        return view('admin.reservations.index', compact('campaign'));
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
            'admin/reservations/'.$reservation->id.'/travel' => 'Travel',
            'admin/reservations/'.$reservation->id.'/costs' => 'Pricing',
            'admin/reservations/'.$reservation->id.'/legal' => 'Legal',
            'admin/reservations/'.$reservation->id.'/resources' => 'Resources'
        ];

        $group = CampaignGroup::where('campaign_id', $reservation->trip->campaign_id)
            ->where('group_id', $reservation->trip->group_id)
            ->firstOrFail();

        return view('admin.reservations.' . $tab, compact('reservation', 'rep', 'tab', 'locked', 'pageLinks', 'group'));
    }

    /**
     * Edit the specified reservation
     *
     * @param $reservationId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($reservationId)
    {
        $reservation = $this->reservation->findOrFail($reservationId);

        $this->authorize('update', $reservation);

        $this->seo()->setTitle('Edit Reservation');

        return view('admin.reservations.edit', compact('reservation'));
    }

    /**
     * Create a new reservation.
     */
    public function create($tripId)
    {
        $this->seo()->setTitle('New Reservation');
        $this->authorize('create', $this->reservation);

        $trip = Trip::findOrFail($tripId);
        $group = CampaignGroup::whereCampaignId($trip->campaign_id)
            ->whereGroupId($trip->group_id)
            ->firstOrFail();

        return view('admin.reservations.create', compact('trip', 'group'));
    }

    public function transfer($reservationId)
    {
        $reservation = $this->reservation->findOrFail($reservationId);

        return view('admin.reservations.transfer', compact('reservation'));
    }

    public function dropped($campaignId)
    {
        $this->authorize('view', $this->reservation);

        $this->seo()->setTitle('Reservations');

        $campaign = Campaign::findOrFail($campaignId);

        return view('admin.reservations.dropped', compact('campaign'));
    }
}
