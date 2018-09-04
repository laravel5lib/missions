<?php

namespace App\Providers;

use App\Models\v1\Passport;
use App\Policies\PassportPolicy;
use Illuminate\Support\Facades\Gate;
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

        Gate::policy(Passport::class, PassportPolicy::class);

        Passport::deleting(function ($passport) {
            $passport->detachFromAllReservations();
        });
    }
}
