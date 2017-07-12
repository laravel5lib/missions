<?php

namespace App\Providers;

use League\Glide\Server;
use App\TransactionHandler;
use App\Models\v1\Reservation;
use League\Glide\ServerFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Relations\Relation;

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
            'influencer_applications' => \App\Models\v1\Essay::class,
            'medical_releases' => \App\Models\v1\MedicalRelease::class,
            'referrals' => \App\Models\v1\Referral::class,
            'questionnaires' => \App\Models\v1\Questionnaire::class,
            'arrival_designations' => \App\Models\v1\Questionnaire::class,
            'airport_preferences' => \App\Models\v1\Questionnaire::class,
            'medical_credentials' => \App\Models\v1\Credential::class,
            'media_credentials' => \App\Models\v1\Credential::class,
            'minor_releases' => \App\Models\v1\Questionnaire::class,
            'notes' => \App\Models\v1\Note::class,
            'todos' => \App\Models\v1\Todo::class,
            'transports' => \App\Models\v1\Transport::class,
            'travel_itineraries' => \App\Models\v1\Activity::class,
            'itineraries' => \App\Models\v1\Itinerary::class,
            'hubs' => \App\Models\v1\Hub::class,
            'plans' => \App\Models\v1\RoomingPlan::class,
            'rooms' => \App\Models\v1\Room::class,
            'regions' => \App\Models\v1\Region::class,
            'accommodations' => \App\Models\v1\Accommodation::class,
            'teams' => \App\Models\v1\Team::class,
            'campaign_transports' => \App\CampaignTransport::class
        ]);

        Validator::extend('is_csv',function($attribute, $value, $params, $validator) {
            $file = base64_decode($value);
            $f = finfo_open();
            $result = finfo_buffer($f, $file, FILEINFO_MIME_TYPE);
            return $result == 'text/csv';
        });

        Validator::extend('is_compatable',function($attribute, $value, $params, $validator) {

            if (isset($params[0])) {
                $reservation = Reservation::find($params[0]);

                return $reservation ? Reservation::whereHas('trip', function($trip) use($reservation) {
                            return $trip->where('campaign_id', $reservation->trip->campaign_id)
                                        ->where('group_id', $reservation->trip->group_id);
                        })->where('id', $value)->first() : false;
            }
            
            return false;
        });

        Validator::extend('within_companion_limit',function($attribute, $value, $params, $validator) {
            
            if (isset($params[0])) {
                
                $companion = Reservation::with('companionReservations')->find($value);
                $reservation = Reservation::with('companionReservations')->find($params[0]);

                if ($companion->companionReservations->count()) {
                    $companion_limit = $companion->companionReservations->min('companion_limit');
                    $companion_count = $companion->companionReservations->count();
                } else {
                    $companion_limit = $companion->companion_limit;
                    $companion_count = 0;
                }

                if ($reservation->companionReservations->count()) {
                    $reservation_limit = $reservation->companionReservations->min('companion_limit');
                    $reservation_count = $reservation->companionReservations->count();
                } else {
                    $reservation_limit = $reservation->companion_limit;
                    $reservation_count = 0;
                }

                $limit = collect([
                    ($companion_limit - $companion_count), 
                    ($reservation_limit - $reservation_count)
                ])->min();

                return $limit > 0;
            }

            return false;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Bugsnag
        $this->app->alias('bugsnag.logger', \Illuminate\Contracts\Logging\Log::class);
        $this->app->alias('bugsnag.logger', \Psr\Log\LoggerInterface::class);

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
