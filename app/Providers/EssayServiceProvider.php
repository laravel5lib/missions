<?php

namespace App\Providers;

use App\Models\v1\Essay;
use App\Policies\EssayPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Models\v1\InfluencerApplication;
use App\Policies\InfluencerApplicationPolicy;
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

        Gate::policy(Essay::class, EssayPolicy::class);
        Gate::policy(InfluencerApplication::class, InfluencerApplicationPolicy::class);

        Essay::deleting(function ($essay) {
            $essay->detachFromAllReservations();
        });

        InfluencerApplication::deleting(function ($essay) {
            $essay->detachFromAllReservations();
        });
    }
}
