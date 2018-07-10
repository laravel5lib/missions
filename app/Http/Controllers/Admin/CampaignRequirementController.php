<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\v1\Campaign;
use Illuminate\Http\Request;

class CampaignRequirementController extends Controller
{
    public function create($id, Request $request)
    {
        $campaign = Campaign::findOrFail($id);

        return view('admin.campaigns.tabs.requirements.create', compact('campaign'));
    }
}
