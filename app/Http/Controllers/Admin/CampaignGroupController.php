<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\v1\CampaignGroup;
use App\Http\Controllers\Controller;

class CampaignGroupController extends Controller
{
    public function show($group, $tab = 'details')
    {
        $group = CampaignGroup::whereUuid($group)->firstOrFail();

        return view('admin.campaigns.groups.tabs.'.$tab, compact('group'));
    }

    public function edit($group)
    {
        $group = CampaignGroup::whereUuid($group)->firstOrFail();

        return view('admin.campaigns.groups.edit', compact('group'));   
    }
}
