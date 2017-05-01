<?php

namespace App\Providers;

use App\Services\Rooming\RoomingPlan;
use Illuminate\Support\ServiceProvider;

class RoomingPlanServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('roomingPlan', function ($app) {
            return new RoomingPlan();
        });
    }
}
