<?php

namespace App\Providers;

use App\Models\v1\MediaCredential;
use App\Models\v1\MedicalCredential;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class CredentialServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'medical_credentials' => MedicalCredential::class,
            'media_credentials' => MediaCredential::class,
        ]);

        MedicalCredential::deleting(function ($credential) {
            $credential->detachFromAllReservations();
        });

        MediaCredential::deleting(function ($credential) {
            $credential->detachFromAllReservations();
        });
    }
}