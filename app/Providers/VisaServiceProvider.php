<?php

namespace App\Providers;

use App\Models\v1\Visa;
use App\Policies\VisaPolicy;
use Illuminate\Support\Facades\Gate;
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

        Gate::policy(Visa::class, VisaPolicy::class);

        Visa::deleting(function ($visa) {
            $visa->detachFromAllReservations();
        });
    }
}
