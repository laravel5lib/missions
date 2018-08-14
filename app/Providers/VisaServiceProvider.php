<?php

namespace App\Providers;

use App\Models\v1\Visa;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class VisaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap(['visas' => Visa::class]);

        Visa::deleting(function ($visa) {
            $visa->detachFromAllReservations();
        });
    }
}
