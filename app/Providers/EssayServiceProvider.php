<?php

namespace App\Providers;

use App\Models\v1\Essay;
use Illuminate\Support\ServiceProvider;
use App\Models\v1\InfluencerApplication;
use Illuminate\Database\Eloquent\Relations\Relation;

class EssayServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'essays' => Essay::class,
            'influencer_applications' => InfluencerApplication::class,
        ]);

        Essay::deleting(function ($essay) {
            $essay->detachFromAllReservations();
        });

        InfluencerApplication::deleting(function ($essay) {
            $essay->detachFromAllReservations();
        });
    }
}
