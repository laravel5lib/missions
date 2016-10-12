<?php

namespace App\Providers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use League\Glide\Server;
use League\Glide\ServerFactory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'App\Models\v1\Fundraiser',
            'App\Models\v1\Group',
            'App\Models\v1\Trip',
            'App\Models\v1\User',
            'App\Models\v1\Reservation',
            'App\Models\v1\Assignment',
            'App\Models\v1\Campaign',
            'App\Models\v1\Upload',
            'App\Models\v1\ProjectPackage'
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // register and configure media server.
        $this->app->singleton(Server::class, function () {

            return ServerFactory::create([
                'source' => Storage::disk('s3')->getDriver(),
                'cache' => Storage::disk('local')->getDriver(),
                'source_path_prefix' => 'images',
                'cache_path_prefix' => 'images/.cache',
                'base_url' => 'api/images',
                'max_image_size' => 2000*2000
            ]);
        });
    }
}
