<?php

namespace App\Providers;

use App\Models\v1\Credential;
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
            'medical_credentials' => Credential::class,
            'media_credentials' => Credential::class
        ]);
    }
}
