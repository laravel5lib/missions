<?php

namespace App\Http\Controllers\Admin;

use App\Models\v1\Campaign;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CampaignPriceController extends Controller
{
    public function show($id, $price)
    {
        $campaign = Campaign::findOrFail($id);
        $price = $campaign->prices()->whereUuid($price)->firstOrFail();

        return view('admin.campaigns.tabs.prices.show', compact('campaign', 'price'));
    }
}
