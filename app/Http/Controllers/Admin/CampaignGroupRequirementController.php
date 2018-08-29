<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\v1\CampaignGroup;
use App\Http\Controllers\Controller;

class CampaignGroupRequirementController extends Controller
{
    public function create($groupId)
    {
        $group = CampaignGroup::whereUuid($groupId)->firstOrFail();

        return view('admin.campaigns.groups.tabs.requirements.create', compact('group'));
    }

    public function show($groupId, $id)
    {
        $group = CampaignGroup::whereUuid($groupId)->firstOrFail();

        $requirement = $group->requireables()
            ->withCount([
                'trips' => function ($query) use ($group) {
                    $query->where('campaign_id', $group->campaign_id)->where('group_id', $group->group_id);
                }, 
                'reservations' => function ($query) use ($group) {
                    $query->whereHas('trip', function ($trip) use ($group) {
                        return $trip->where('campaign_id', $group->campaign_id)->where('group_id', $group->group_id);
                    });
                }
            ])
            ->findOrFail($id);

        return view('admin.campaigns.groups.tabs.requirements.show', compact('group', 'requirement'));
    }

    public function edit($groupId, $id)
    {
        $group = CampaignGroup::whereUuid($groupId)->firstOrFail();

        $requirement = $group->requireables()->findOrFail($id);

        return view('admin.campaigns.groups.tabs.requirements.edit', compact('group', 'requirement'));
    }
}
