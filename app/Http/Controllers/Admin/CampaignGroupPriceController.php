<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\v1\CampaignGroup;
use App\Http\Controllers\Controller;

class CampaignGroupPriceController extends Controller
{
    public function show($group, $price)
    {
        $group = CampaignGroup::whereUuid($group)->firstOrFail();

        $price = $group->prices()
            ->whereUuid($price)
            ->with('payments')
            ->firstOrFail();
        
        return view('admin.campaigns.groups.tabs.prices.show', compact('group', 'price'));
    }
}
