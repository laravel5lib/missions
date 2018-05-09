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

        $meta = collect($group->meta)->keyBy('label')->map(function($item) {
            return $item['value'];
        })->all();

        return view('admin.campaigns.groups.tabs.'.$tab, compact('group', 'meta'));
    }

    public function edit($group)
    {
        $group = CampaignGroup::whereUuid($group)->firstOrFail();

        return view('admin.campaigns.groups.edit', compact('group'));   
    }
}
