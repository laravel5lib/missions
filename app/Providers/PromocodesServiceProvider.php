<?php

namespace App\Providers;

use App\Services\Promocodes;
use Illuminate\Support\ServiceProvider;

class PromocodesServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('promocodes', function ($app) {
            return new Promocodes();
        });
    }
}