<?php

namespace App\Http\Controllers\Admin;

use App\Models\v1\Trip;
use Illuminate\Http\Request;
use App\Models\v1\CampaignGroup;
use App\Http\Controllers\Controller;

class TripPriceController extends Controller
{
    public function show($id, $price)
    {
        $trip = Trip::withCount('reservations')->findOrFail($id);
        $price = $trip->priceables()
                      ->whereUuid($price)
                      ->with('payments')
                      ->withCount('reservations')
                      ->firstOrFail();

        $group = CampaignGroup::where('campaign_id', $trip->campaign_id)
            ->where('group_id', $trip->group_id)
            ->firstOrFail();

        return view('admin.trips.tabs.prices.show', compact('trip', 'price', 'group'));
    }
}
