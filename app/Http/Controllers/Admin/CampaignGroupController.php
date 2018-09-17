<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\v1\TripTemplate;
use App\Models\v1\CampaignGroup;
use App\Http\Controllers\Controller;

class CampaignGroupController extends Controller
{
    /**
     * Show the campaign group details page(s)
     * 
     * @param  string $group
     * @param  string $tab
     * @return response
     */
    public function show($group, $tab = 'details')
    {
        $group = CampaignGroup::whereUuid($group)->firstOrFail();

        return view('admin.campaigns.groups.tabs.'.$tab, $this->getViewData($tab, $group));
    }

    /**
     * Get the data that should be passed to the view.
     * 
     * @param  string $tab
     * @param  string $group
     * @return array
     */
    private function getViewData($tab, $group)
    {
        $meta = collect($group->meta)->keyBy('label')->map(function($item) {
            return $item['value'];
        })->all();

        $data = [
            'group' => $group,
            'meta' => $meta
        ];

        if ($tab == 'trips') {
            $data['templates'] = TripTemplate::pluck('name', 'id');
        }

        return $data;
    }

    /**
     * Show the edit form for the campaign group.
     * 
     * @param  string $group
     * @return response
     */
    public function edit($group)
    {
        $group = CampaignGroup::whereUuid($group)->firstOrFail();

        return view('admin.campaigns.groups.edit', compact('group'));   
    }
}
