<?php

namespace App\Http\Controllers\Admin;

use App\Models\v1\Region;
use App\Models\v1\Campaign;
use Illuminate\Http\Request;
use App\Models\v1\SquadMember;
use App\Utilities\v1\TeamRole;
use App\Http\Controllers\Controller;

class RegionController extends Controller
{
    public function create($campaignId)
    {
        $campaign = Campaign::findOrFail($campaignId);

        return view('admin.regions.create', compact('campaign'));
    }

    public function show($campaignId, $regionId)
    {
        $campaign = Campaign::findOrFail($campaignId);
        $region = Region::withCount(['squads', 'members'])->findOrFail($regionId);
        $squadIds = $region->squads()->pluck('id');

        $leaders = SquadMember::whereIn('squad_id', $squadIds)->whereHas('reservation', function ($query) {
            return $query->whereIn('desired_role', ['PRDR', 'PRAS', 'CODR', 'MCDR', 'SSPK']);
        })->with('reservation.trip.group')->get();

        $organizations = $this->getOrganizations($region);
        $roles = $this->getRoles($region);
        $squads = $this->getSquads($region);

        return view('admin.regions.show', compact('campaign', 'region', 'leaders', 'organizations', 'roles', 'squads'));
    }

    private function getOrganizations($region)
    {
        return $region->squads()
            ->with('members.reservation.trip.group')
            ->get()
            ->pluck('members')
            ->flatten()
            ->groupBy('reservation.trip.group.id')
            ->map(function ($members) {      
                return [
                    'name' => $members[0]['reservation']['trip']['group']['name'],
                    'squads' => collect($members)->groupBy('squad_id')->count(),
                    'missionaries' => $members->count()
                ];
            })->sortByDesc('missionaries')->all();
    }

    private function getRoles($region)
    {
        return $region->squads()
            ->with('members.reservation')
            ->get()
            ->pluck('members')
            ->flatten()
            ->groupBy('reservation.desired_role')
            ->map(function ($roles) {      
                return [
                    'name' => teamRole($roles[0]['reservation']['desired_role']),
                    'count' => $roles->count()
                ];
            })->sortByDesc('count')->all();
    }

    private function getSquads($region)
    {
        return $region->squads()
            ->with('members')
            ->get()
            ->groupBy('id')
            ->map(function ($squad) {    
                return [
                    'callsign' => $squad[0]['callsign'],
                    'missionaries' => count($squad[0]['members'])
                ];
            })->sortByDesc('missionaries')->all();
    }

    public function edit($campaignId, $regionId)
    {
        $campaign = Campaign::findOrFail($campaignId);
        $region = Region::findOrFail($regionId);

        return view('admin.regions.edit', compact('campaign', 'region'));
    }
}
