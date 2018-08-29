<?php

namespace App\Providers;

use App\Models\v1\MedicalRelease;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class MedicalReleaseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap(['medical_releases' => MedicalRelease::class]);

        MedicalRelease::deleting(function ($release) {
            $release->detachFromAllReservations();
        });
    }
}
