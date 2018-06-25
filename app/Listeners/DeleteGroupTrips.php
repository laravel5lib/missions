<?php

namespace App\Listeners;

use App\Models\v1\Trip;
use App\Events\GroupRemoved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeleteGroupTrips
{
    /**
     * Handle the event.
     *
     * @param  GroupRemoved  $event
     * @return void
     */
    public function handle(GroupRemoved $event)
    {
        $trips = Trip::where('campaign_id', $event->campaign_id)
            ->where('group_id', $event->group_id)
            ->get();
        
        $trips->each(function ($trip) {
            $trip->delete();
        });
    }
}
