<?php

namespace App\Providers;

use App\Models\v1\Trip;
use League\Glide\Server;
use App\Models\v1\Campaign;
use App\TransactionHandler;
use Laravel\Horizon\Horizon;
use App\Models\v1\Reservation;
use Laravel\Passport\Passport;
use App\Observers\TripObserver;
use League\Glide\ServerFactory;
use App\Observers\CampaignObserver;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use App\Observers\ReservationObserver;
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
        Campaign::observe(CampaignObserver::class);
        Trip::observe(TripObserver::class);
        Reservation::observe(ReservationObserver::class);

        Relation::morphMap([
            'fundraisers' => \App\Models\v1\Fundraiser::class,
            'campaign-groups' => \App\Models\v1\CampaignGroup::class,
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
            'itineraries' => \App\Models\v1\FlightItinerary::class,
            'flights' => \App\Models\v1\Flight::class,
            'hubs' => \App\Models\v1\Hub::class,
            'plans' => \App\Models\v1\RoomingPlan::class,
            'rooms' => \App\Models\v1\Room::class,
            'regions' => \App\Models\v1\Region::class,
            'accommodations' => \App\Models\v1\Accommodation::class,
            'teams' => \App\Models\v1\Team::class,
            'campaign_transports' => \App\CampaignTransport::class
        ]);

        Validator::extend('country', function ($attribute, $value, $params, $validator) {
            return in_array($value, array_values(\App\Utilities\v1\Country::all()));
        }, 'Please provide a valid country code.');

        Validator::extend('is_csv', function ($attribute, $value, $params, $validator) {
            $file = base64_decode($value);
            $f = finfo_open();
            $result = finfo_buffer($f, $file, FILEINFO_MIME_TYPE);
            return $result == 'text/csv';
        });

        Validator::extend('is_compatible', function ($attribute, $value, $params, $validator) {

            if (isset($params[0])) {
                $reservation = Reservation::find($params[0]);

                return $reservation ? Reservation::whereHas('trip', function ($trip) use ($reservation) {
                            return $trip->where('campaign_id', $reservation->trip->campaign_id)
                                        ->where('group_id', $reservation->trip->group_id);
                })->where('id', $value)->first() : false;
            }

            return false;
        });

        Validator::extend('within_companion_limit', function ($attribute, $value, $params, $validator) {

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

        Blade::component('components.breadcrumbs', 'breadcrumbs');
        Blade::component('components.sidenav', 'sidenav');

        Blade::directive('prod', function ($beta) {
            return "<?php if (app()->environment('production')): ?>";
        });

        Blade::directive('endprod', function ($beta) {
            return "<?php endif; ?>";
        });

        Blade::directive('stage', function ($beta) {
            return "<?php if (app()->environment('staging')): ?>";
        });

        Blade::directive('endstage', function ($beta) {
            return "<?php endif; ?>";
        });

        Route::macro('angelHouseRoutes', function () {

            $urls = [
                '12angelhouse',
                'a-heather-co-fresh-water',
                'a-step-toward-freedom-house',
                'ab-angelhouse',
                'abbyandgraceshop',
                'ahco-op',
                'ahomeforgood',
                'allisonswaterwell',
                'anchoredhope',
                'anewfuture',
                'angelhouseofhope',
                'angels-indeed!!',
                'angeltaylor',
                'angies-blessings-house',
                'antiochchurchwaterwell',
                'arcwoodandtimbersproject',
                'balabeads',
                'bbbaccarini',
                'behefamilywell',
                'bellyandbabe',
                'bringthemhome',
                'brokenchains',
                'buildingafuture',
                'buildyourhouseofmiracles',
                'carolinesgracewater',
                'childofdestinyangelhouse',
                'clara-diaz-de-moreno-water-well-project',
                'clarashome',
                'crosbysbuildanorphanage2017',
                'crownofbeautyorphanage',
                'danielleandmaryswaterwell',
                'dba-dreamhouse-project',
                'dbadreamhouseorphanage',
                'divinepresence',
                'dominicandmary',
                'donatenow',
                'everychildneedsahome',
                'faith-for-50',
                'fightforfreedom',
                'foreverchanged-mewaterwellproject',
                'friendsoflititzpa',
                'georgesfundraiser',
                'glorias-house-project',
                'graceannemeyer',
                'gracetemplechurchwaterwell',
                'hairdressers4hope',
                'heal-broken-hearted-project',
                'hearthouse',
                'help-gray-build-an-angel-house',
                'holland',
                'hopesplace',
                'hornfamily',
                'house-of-blessings-project',
                'hudsons-house-project',
                'janashouse',
                'jarsofclaychildrenshome',
                'jaxsonsfreshwaterwell',
                'jcswaterwellproject',
                'jimscottandsons',
                'john4-14-freshwaterwell',
                'judahchristianschool',
                'kerbiandconnersangels',
                'kimberlyliao',
                'kress-family-project',
                'lauren-blairs-orphanage-project',
                'leonardbloodwell',
                'lifestylepublications',
                'little-loving-hands-addiontional-items-project',
                'little-loving-hands-orphanage-project',
                'liv-amsrud-project',
                'livelyinindiaorphanrescue',
                'livlouise',
                'loveforachild',
                'mallipur-church-project',
                'marywaterwell',
                'mayesangelhouse',
                'meehanangelhouse',
                'mermaidangels',
                'misfits',
                'mjangelhouse',
                'moellerangelhouse',
                'newgenerationofmenangelhouse',
                'newhopeangelhouse',
                'nhimapparelorphanage2015',
                'orphanstoangels',
                'paul-davis-church-project',
                'paulandjuliedavis',
                'pavlakpassion',
                'paytenandmadelynswellproject',
                'phils-friendsangelhouse',
                'press-family-angel-house',
                'project50',
                'projectsmiley',
                'quinnsorphanage',
                'REMAX1',
                'reva-abbys-orphanage-project',
                'rivas-h2o-project',
                'robbinsnest',
                'robbinsnest2014',
                'rochester-christian-church-orphanage-project',
                'ross-schoenherr-chris-yatoomas-home-project',
                'safehome',
                'saving-the-children-of-india-project',
                'scfangelhouse',
                'shadowofhiswings4',
                'sharptoothstudioangelhouse',
                'shilohsprings',
                'skylynn-nikels-well-project',
                'spreadchristslove',
                'startupsnext-project',
                'teambhimji2014',
                'theashleyproject',
                'thematthew1810home',
                'thewarren5',
                'underhiswings-christswaterwell',
                'warnerfamilywell',
                'waterforprojectsmiley',
                'welcomehomeangels',
                'welloflivingwaters',
                'withlovefromkansas',
                'wwc',
                'wwcsafehomenepal'
            ];

            foreach($urls as $url) {
                Route::get($url, function () use($url) {
                    return redirect('https://angelhouse.me/fundraisers/'. $url);
                });
            }
        });

        Horizon::auth(function ($request) {
            return optional($request->user())->email == 'nkeena1@gmail.com';
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Passport::ignoreMigrations(); // using modified migrations

        // Bugsnag
        $this->app->alias('bugsnag.logger', \Psr\Log\LoggerInterface::class);
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
