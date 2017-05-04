<?php

namespace App\Providers;

use App\Models\v1\RoomingPlan as Model;
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
            return new RoomingPlan(new Model);
        });
    }
}
