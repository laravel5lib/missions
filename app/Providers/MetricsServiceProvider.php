<?php

namespace App\Providers;

use App\Models\v1\Team;
use App\Models\v1\TeamType;
use App\Services\Metrics\Teams;
use Illuminate\Support\ServiceProvider;

class MetricsServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
         $this->app->singleton('teams', function ($app) {
            return new Teams(new Team, new TeamType);
         });
    }
}
