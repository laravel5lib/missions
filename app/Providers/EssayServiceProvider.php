<?php

namespace App\Providers;

use App\Models\v1\Essay;
use Illuminate\Support\ServiceProvider;
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
            'influencer_applications' => Essay::class,
        ]);

        Essay::deleting(function ($essay) {
            $essay->detachFromAllReservations();
        });
    }
}
