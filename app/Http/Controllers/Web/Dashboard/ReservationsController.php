<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\v1\Reservation;
use App\Models\v1\SquadMember;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Traits\SEOTools;
use Dingo\Api\Exception\InternalHttpException;

class ReservationsController extends Controller
{
    use SEOTools;

    public function index()
    {
        $this->seo()->setTitle('Reservations');

        return view('dashboard.reservations.index');
    }

    public function show($id, $tab = 'details')
    {
        $reservation = Reservation::findOrFail($id);

        $rep = $reservation->rep ? $reservation->rep : $reservation->trip->rep;

        $locked = $reservation->trip->campaign->reservations_locked;

        $title = $reservation->name.'\'s Reservation ' . title_case(str_replace("-", " ", $tab));
        $this->seo()->setTitle($title);

        $data = $this->loadPageData($tab, $reservation);

        return view('dashboard.reservations.' . $tab, compact('reservation', 'rep', 'tab', 'locked'))->with($data);
    }

    private function loadPageData($tab, $reservation)
    {
        if ($tab == 'squad') {
            return $this->getSquadData($reservation);
        }

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
}
