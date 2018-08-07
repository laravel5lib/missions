<?php

namespace App\Http\Controllers\Admin;

use App\Models\v1\Trip;
use App\Models\v1\Campaign;
use Illuminate\Http\Request;
use App\Models\v1\Reservation;
use App\Models\v1\SquadMember;
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

        $totals = [
            'all' => $campaign->reservations()->count(),
            'deposited' => $campaign->reservations()->deposited()->count(),
            'process' => $campaign->reservations()->inProcess()->count(),
            'funded' => $campaign->reservations()->funded()->count(),
            'ready' => $campaign->reservations()->ready()->count()
        ];

        return view('admin.reservations.index', compact('campaign', 'totals'));
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
        $reservation = Reservation::withTrashed()->findOrFail($id);

        $this->authorize('view', $reservation);

        $title = $reservation->name . '\'s Reservation ' . title_case(str_replace("-", " ", $tab));
        $this->seo()->setTitle($title);

        $rep = $reservation->rep ? $reservation->rep : $reservation->trip->rep;

        $group = CampaignGroup::where('campaign_id', $reservation->trip->campaign_id)
            ->where('group_id', $reservation->trip->group_id)
            ->firstOrFail();

        $pageLinks = [
            'admin/reservations/'.$reservation->id => 'Overview',
            'admin/reservations/'.$reservation->id.'/funding' => 'Funding',
            'admin/reservations/'.$reservation->id.'/requirements' => 'Requirements',
            'admin/reservations/'.$reservation->id.'/travel' => 'Travel',
            'admin/reservations/'.$reservation->id.'/squad' => 'Squad',
            'admin/reservations/'.$reservation->id.'/costs' => 'Pricing',
            'admin/reservations/'.$reservation->id.'/legal' => 'Legal',
            'admin/reservations/'.$reservation->id.'/resources' => 'Resources'
        ];

        $locked = $reservation->trip->campaign->reservations_locked;
        
        $data = $this->loadPageData($tab, $reservation);

        return view('admin.reservations.'.$tab, compact('reservation', 'rep', 'pageLinks', 'locked', 'group'))->with($data);
    }

    private function loadPageData($tab, $reservation)
    {
        $data = [
            'squad' => 'getSquadData',
            'details' => 'getData',
            'funding' => 'getData',
            'requirements' => 'getData',
            'travel' => 'getData',
            'costs' => 'getData',
            'legal' => 'getData',
            'resources' => 'getData'
        ];

        return $this->{$data[$tab]}($reservation);
    }

    private function getData($reservation)
    {
        return [];
    }

    private function getSquadData($reservation)
    {
        $membership = $reservation
                ->squadMemberships()
                ->with('squad.members.reservation.user')
                ->first();

        if (! $membership) return ['membership' => null, 'leaders' => collect([]), 'groupMembers' => collect([])];
        
        $squadLeaders = SquadMember::where('squad_id', $membership->squad_id)->whereHas('reservation', function ($query) {
            return $query->whereIn('desired_role', ['TMLR', 'MCDR']);
        })->with('reservation')->get();

        $squadIds = $membership->squad->region->squads->pluck('id')->toArray();

        $regionalLeaders = SquadMember::whereIn('squad_id', $squadIds)->whereHas('reservation', function ($query) {
            return $query->whereIn('desired_role', ['PRDR', 'PRAS']);
        })->with('reservation')->get();

        $leaders = $regionalLeaders->merge($squadLeaders);

        $groupMembers = SquadMember::where('squad_id', $membership->squad_id)
            ->where('group', $membership->group)
            ->with('reservation')
            ->get()
            ->reject(function ($member) use ($reservation) {
                return $member->reservation_id == $reservation->id;
            });
        
        return ['membership' => $membership, 'leaders' => $leaders, 'groupMembers' => $groupMembers];
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
