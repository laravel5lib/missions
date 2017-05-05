<?php

namespace App\Providers;

use App\Models\v1\RoomingPlan;
use App\Services\Rooming\Room;
use App\Models\v1\Accommodation;
use App\Models\v1\Room as RoomModel;
use Illuminate\Support\ServiceProvider;

class RoomServiceProvider extends ServiceProvider
{

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('room', function ($app) {
            return new Room(new RoomModel, new RoomingPlan, new Accommodation);
        });
    }
}
