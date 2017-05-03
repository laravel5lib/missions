<?php

namespace App\Providers;

use App\Services\TravelActivity;
use Illuminate\Support\ServiceProvider;

class TravelActivityServiceProvider extends ServiceProvider
{

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('TravelActivity', function ($app) {
            return new TravelActivity();
        });
    }
}
