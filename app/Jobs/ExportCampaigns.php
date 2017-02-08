<?php

namespace App\Jobs;

use App\Models\v1\Campaign;

class ExportCampaigns extends Exporter
{
    public function data($request)
    {
        return Campaign::filter($request)
                ->with('slug', 'groups', 'fund')
                ->get();
    }

    public function columns($campaign)
    {
        return [
            'id' => $campaign->id,
            'name' => $campaign->name,
            'country' => country($campaign->country_code),
            'country_code' => $campaign->country_code,
            'groups' => $campaign->groups->count(),
            'fund' => $campaign->fund ? $campaign->fund->name : null,
            'short_desc' => $campaign->short_desc,
            'url' => $campaign->slug ? $campaign->slug->url : null,
            'page_filename' => $campaign->page_src,
            'avatar_filename' => $campaign->avatar ? $campaign->avatar->source : null,
            'banner_filename' => $campaign->banner ? $campaign->banner->source : null,
            'started_at' => $campaign->started_at->toDateTimeString(),
            'ended_at' => $campaign->ended_at->toDateTimeString(),
            'created' => $campaign->created_at->toDateTimeString(),
            'updated' => $campaign->updated_at->toDateTimeString(),
        ];
    }
}
