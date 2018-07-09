<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\v1\CampaignGroup;
use App\Models\v1\Trip;
use Illuminate\Http\Request;

class CampaignGroupPriceController extends Controller
{
    public function show($group, $price)
    {
        $group = CampaignGroup::whereUuid($group)->firstOrFail();

        $price = $group->prices()
            ->whereUuid($price)
            ->with('payments')
            ->withCount('reservations', 'trips')
            ->firstOrFail();

        $trips = Trip::where('group_id', $group->group_id)
            ->where('campaign_id', $group->campaign_id)
            ->withCount('reservations')
            ->get();

        $trips_count = count($trips);
        $reservations_count = $trips->sum('reservations_count');
        
        return view('admin.campaigns.groups.tabs.prices.show', compact('group', 'price', 'trips_count', 'reservations_count'));
    }
}
