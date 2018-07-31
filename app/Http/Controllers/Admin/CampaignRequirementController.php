<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\v1\Campaign;
use Illuminate\Http\Request;

class CampaignRequirementController extends Controller
{   
    /**
     * Show form to create a new requirement
     * 
     * @param  string  $campaignId 
     * @param  Illuminate\Http\Request $request    
     * @return Illuminate\Http\Response              
     */
    public function create($campaignId, Request $request)
    {
        $campaign = Campaign::findOrFail($campaignId);

        return view('admin.campaigns.tabs.requirements.create', compact('campaign'));
    }

    /**
     * Show a page for a specific requirement.
     * 
     * @param  string $campaignId 
     * @param  string $id         
     * @return Illuminate\Http\Response             
     */
    public function show($campaignId, $id)
    {
        $campaign = Campaign::withCount(['groups', 'trips', 'reservations'])->findOrFail($campaignId);

        $requirement = $campaign->requireables()->withCount(['groups', 'trips', 'reservations'])->findOrFail($id);

        return view('admin.campaigns.tabs.requirements.show', compact('campaign', 'requirement'));
    }

    /**
     * Show form to create a new requirement.
     * 
     * @param  string  $campaignId 
     * @param  Illuminate\Http\Request $request    
     * @return Illuminate\Http\Response              
     */
    public function edit($campaignId, $id, Request $request)
    {
        $campaign = Campaign::findOrFail($campaignId);

        $requirement = $campaign->requirements()->findOrFail($id);

        return view('admin.campaigns.tabs.requirements.edit', compact('campaign', 'requirement'));
    }
}
