<?php

namespace App\Providers;

use App\TransactionHandler;
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
            'fundraisers' => \App\Models\v1\Fundraiser::class,
            'groups' => \App\Models\v1\Group::class,
            'trips' => \App\Models\v1\Trip::class,
            'users' => \App\Models\v1\User::class,
            'reservations' => \App\Models\v1\Reservation::class,
            'assignments' => \App\Models\v1\Assignment::class,
            'campaigns' => \App\Models\v1\Campaign::class,
            'uploads' => \App\Models\v1\Upload::class,
            'project_initiatives' => \App\Models\v1\ProjectInitiative::class,
            'project_causes' => \App\Models\v1\ProjectCause::class,
            'projects' => \App\Models\v1\Project::class,
            'transactions' => \App\Models\v1\Transaction::class,
            'funds' => \App\Models\v1\Fund::class,
            'donors' => \App\Models\v1\Donor::class,
            'trip_interests' => \App\Models\v1\TripInterest::class,
            'passports' => \App\Models\v1\Passport::class,
            'visas' => \App\Models\v1\Visa::class,
            'essays' => \App\Models\v1\Essay::class,
            'medical_releases' => \App\Models\v1\MedicalRelease::class,
            'referrals' => \App\Models\v1\Referral::class,
            'questionnaires' => \App\Models\v1\Questionnaire::class,
            'arrival_designations' => \App\Models\v1\Questionnaire::class,
            'airport_preferences' => \App\Models\v1\Questionnaire::class,
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // register and configure the transaction handler.
        $this->app->singleton('App\TransactionHandler', function ($app) {
            return new TransactionHandler(
                $app->make('App\Models\v1\Transaction'),
                $app->make('App\Services\PaymentGateway'),
                $app->make('App\Models\v1\Donor'),
                $app->make('App\Models\v1\Fund')
            );
        });

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
