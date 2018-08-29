<?php

namespace App\Providers;

use App\Models\v1\Fund;
use App\Models\v1\Lead;
use App\Models\v1\Fundraiser;
use Illuminate\Support\Facades\Route;
use App\Models\v1\InfluencerApplication;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Route::bind('fund', function ($value) {
            return Fund::withTrashed()->findOrFail($value);
        });

        Route::bind('fundraiser', function ($value) {
            return Fundraiser::whereUuid($value)->first();
        });

        Route::bind('lead', function ($value) {
            return Lead::whereUuid($value)->firstOrFail();
        });

        Route::bind('influencer_application', function ($value) {
            return InfluencerApplication::findOrFail($value);
        });
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapAdminRoutes();

        $this->mapWebRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::group([
            'middleware' => ['web', 'impersonate'],
            'namespace' => $this->namespace . '\Web',
        ], function ($router) {
            require base_path('routes/web.php');
        });
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::group([
//            'middleware' => ['api'],
            'namespace' => $this->namespace . '\Api',
            'prefix' => 'api',
        ], function ($router) {
            require base_path('routes/api.php');
        });
    }

    /**
     * Define the "admin" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapAdminRoutes()
    {
        Route::group([
            'middleware' => ['web', 'auth', 'impersonate'],
            'namespace' => $this->namespace . '\Admin',
            'prefix' => 'admin',
        ], function ($router) {
            require base_path('routes/admin.php');
        });
    }
}
