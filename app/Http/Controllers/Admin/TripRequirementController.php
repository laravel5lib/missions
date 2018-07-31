<?php

namespace App\Http\Controllers\Admin;

use App\Models\v1\Trip;
use Illuminate\Http\Request;
use App\Models\v1\CampaignGroup;
use App\Http\Controllers\Controller;

class TripRequirementController extends Controller
{
    public function create($tripId)
    {
        $trip = Trip::findOrFail($tripId);

        $group = CampaignGroup::query()
            ->where('campaign_id', $trip->campaign_id)
            ->where('group_id', $trip->group_id)
            ->firstOrFail();

        return view('admin.trips.tabs.requirements.create', compact('trip', 'group'));
    }

    public function show($tripId, $requirementId)
    {
        $trip = Trip::withCount(['reservations'])->findOrFail($tripId);

        $requirement = $trip->requireables()
            ->withCount([
                'reservations' => function ($query) use ($trip) {
                    $query->where('trip_id', $trip->id);
                }
            ])
            ->findOrFail($requirementId);

        $group = CampaignGroup::query()
            ->where('campaign_id', $trip->campaign_id)
            ->where('group_id', $trip->group_id)
            ->firstOrFail();

        return view('admin.trips.tabs.requirements.show', compact('trip', 'group', 'requirement'));
    }

    public function edit($tripId, $requirementId)
    {
        $trip = Trip::findOrFail($tripId);

        $group = CampaignGroup::query()
            ->where('campaign_id', $trip->campaign_id)
            ->where('group_id', $trip->group_id)
            ->firstOrFail();

        $requirement = $trip->requireables()->findOrFail($requirementId);

        return view('admin.trips.tabs.requirements.edit', compact('trip', 'group', 'requirement'));
    }
}
