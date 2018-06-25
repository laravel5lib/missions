<?php

namespace App\Observers;

use App\Models\v1\Campaign;
use Illuminate\Support\Facades\DB;

class CampaignObserver
{
    public function deleting(Campaign $campaign)
    {
        DB::transaction(function () use ($campaign) {
            $campaign->groups()->detach();

            $campaign->trips->each(function ($trip) {
                $trip->delete();
            });
        });
    }
}