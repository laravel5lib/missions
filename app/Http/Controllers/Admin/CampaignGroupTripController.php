<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\v1\CampaignGroup;
use App\Http\Controllers\Controller;

class CampaignGroupTripController extends Controller
{
    public function create($group)
    {
        $group = CampaignGroup::whereUuid($group)->firstOrFail();
        
        return view('admin.campaigns.groups.tabs.trips.create', compact('group'));
    }
}
