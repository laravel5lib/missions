<?php

namespace App\Http\Controllers\Admin;

use App\Models\v1\Squad;
use App\Models\v1\Campaign;
use Illuminate\Http\Request;
use App\Models\v1\SquadMember;
use App\Utilities\v1\TeamRole;
use App\Http\Controllers\Controller;

class SquadController extends Controller
{
    public function index($campaignId)
    {
        $campaign = Campaign::findOrFail($campaignId);

        $totals = [
            'members' => SquadMember::campaign($campaignId)->count(),
            'squads' => Squad::campaignId($campaignId)->count(),
            'regions' => $campaign->regions()->count(),
            'unassigned' => $campaign->reservations()->squadsCount(0)->count()
        ];

        return view('admin.squads.index', compact('campaign', 'totals'));
    }

    public function create($campaignId)
    {
        $campaign = Campaign::findOrFail($campaignId);

        $regions = $this->getRegions($campaign);

        return view('admin.squads.create', compact('campaign', 'regions'));
    }

    public function show($campaignId, $squadId)
    {
        $campaign = Campaign::findOrFail($campaignId);

        $squad = Squad::whereUuid($squadId)->withCount('members')->firstOrFail();

        $leaders = $squad->members()->whereHas('reservation', function ($query) {
            return $query->whereIn('desired_role', array_keys(TeamRole::leadership()));
        })->with('reservation.trip.group')->get();

        $organizations = $this->getOrganizations($squad);
        $roles = $this->getRoles($squad);

        return view('admin.squads.show', compact('campaign', 'squad', 'leaders', 'organizations', 'roles'));
    }

    private function getOrganizations($squad)
    {
        return $squad->members()
            ->with('reservation.trip.group')
            ->get()
            ->groupBy('reservation.trip.group.id')
            ->map(function ($members) {      
                return [
                    'name' => $members[0]['reservation']['trip']['group']['name'],
                    'squads' => collect($members)->groupBy('squad_id')->count(),
                    'missionaries' => $members->count()
                ];
            })->sortByDesc('missionaries')->all();
    }

    private function getRoles($squad)
    {
        return $squad->members()
            ->with('reservation')
            ->get()
            ->groupBy('reservation.desired_role')
            ->map(function ($roles) {      
                return [
                    'name' => teamRole($roles[0]['reservation']['desired_role']),
                    'count' => $roles->count()
                ];
            })->sortByDesc('count')->all();
    }

    public function edit($campaignId, $squadId)
    {
        $campaign = Campaign::findOrFail($campaignId);

        $squad = Squad::whereUuid($squadId)->firstOrFail();

        $regions = $this->getRegions($campaign);

        return view('admin.squads.edit', compact('campaign', 'squad', 'regions'));
    }

    private function getRegions($campaign)
    {
        return $campaign->regions->keyBy('id')->map(function ($region) {
            return $region->name;
        })->all();
    }
}
