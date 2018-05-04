<?php

namespace App\Http\Controllers\Admin;

use App\Models\v1\Campaign;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CampaignCostController extends Controller
{
    public function show($id, $cost)
    {
        $campaign = Campaign::findOrFail($id);
        $cost = $campaign->costs()->findOrFail($cost);

        return view('admin.campaigns.tabs.costs.show', compact('campaign', 'cost'));
    }
}
