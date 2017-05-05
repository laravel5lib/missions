<?php

namespace App\Providers;

use App\Services\Accounting;
use Illuminate\Support\ServiceProvider;

class AccountingServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('accounting', function ($app) {
            return new Accounting();
        });
    }
}
