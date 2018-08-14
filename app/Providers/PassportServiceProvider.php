<?php

namespace App\Providers;

use App\Models\v1\Passport;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class PassportServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap(['passports' => Passport::class]);

        Passport::deleting(function ($passport) {
            $passport->detachFromAllReservations();
        });
    }
}
