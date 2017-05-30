<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class BackendServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Repositories\Rooming\Interfaces\Type',
            'App\Repositories\Rooming\EloquentType'
        );
        $this->app->bind(
            'App\Repositories\Rooming\Interfaces\Plan',
            'App\Repositories\Rooming\EloquentPlan'
        );
        $this->app->bind(
            'App\Repositories\Rooming\Interfaces\Accommodation',
            'App\Repositories\Rooming\EloquentAccommodation'
        );
        $this->app->bind(
            'App\Repositories\Rooming\Interfaces\Room',
            'App\Repositories\Rooming\EloquentRoom'
        );
        $this->app->bind(
            'App\Repositories\Rooming\Interfaces\Occupant',
            'App\Repositories\Rooming\EloquentOccupant'
        );
    }
}
