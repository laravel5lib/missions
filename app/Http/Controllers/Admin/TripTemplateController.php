<?php

namespace App\Http\Controllers\Admin;

use App\Models\v1\Campaign;
use Illuminate\Http\Request;
use App\Models\v1\TripTemplate;
use App\Http\Controllers\Controller;

class TripTemplateController extends Controller
{
    public function create($campaignId)
    {
        $this->authorize('create', TripTemplate::class);

        $campaign = Campaign::findOrFail($campaignId);

        return view('admin.campaigns.tabs.trip-templates.create', compact('campaign'));
    }

    public function show($campaignId, $id)
    {
        $campaign = Campaign::findOrFail($campaignId);
        $template = $campaign->tripTemplates()->findOrFail($id);

        $this->authorize('view', $template);

        return view('admin.campaigns.tabs.trip-templates.show', compact('campaign', 'template'));
    }

    public function edit($campaignId, $id)
    {
        $campaign = Campaign::findOrFail($campaignId);
        $template = $campaign->tripTemplates()->findOrFail($id);

        $this->authorize('update', $template);

        return view('admin.campaigns.tabs.trip-templates.edit', compact('campaign', 'template'));
    }
}
