<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api'
], function ($api) {
    $api->get('/', function () {
        return [
            'message' => 'Welcome to the Missions.Me API',
        ];
    });

    $api->get('campaigns/{campaign}', 'CampaignsController@show');
    $api->get('trips/{trip}', 'TripsController@show');
    $api->resource('interests', 'TripInterestsController');
    $api->post('contact', 'UtilitiesController@sendContactEmail');
    $api->post('speaker', 'UtilitiesController@sendSpeakerRequestEmail');
    $api->post('sponsor-project', 'UtilitiesController@sendProjectSponsorEmail');
    $api->post('groups/submit', 'GroupsController@submit');
    $api->get('referrals/{id}', 'ReferralsController@show');

    $api->put('representatives/{id}/avatar', 'RepresentativeAvatarController@update');
    $api->resource('representatives', 'RepresentativeController');

    $api->get('download/{path}', function ($path) {
        $headers = [
            'Content-Type' => Storage::mimetype($path),
            'Content-Disposition' => 'attachment',
        ];

        return response()->make(Storage::disk('s3')->get($path), 200, $headers);
    })->where('path', '.+');

    $api->get('play/{path}', function ($path) {
        return response()->make(Storage::disk('s3')->get($path), 200, [
            'Content-Type' => 'audio/mp3'
        ]);
    })->where('path', '.+');


    $api->group(['middleware' => ['api.auth']], function($api) {

        $api->resource('uploads', 'UploadsController');
        $api->get('images/{path}', 'UploadsController@display')->where('path', '.+');

        $api->post('/register', 'AuthenticationController@register');
        $api->post('/login', 'AuthenticationController@authenticate');
        $api->delete('/logout', 'AuthenticationController@deauthenticate');
        $api->post('/refresh', 'AuthenticationController@refresh');
        $api->get('/users/me', 'UsersController@show');
        $api->put('/users/me', 'UsersController@update');
        $api->resource('users', 'UsersController');
        $api->post('users/export', 'UsersController@export');
        $api->post('users/import', 'UsersController@import');
        $api->get('users/{id}/reports', 'UserReportsController@index');
        $api->delete('reports/{id}', 'UserReportsController@destroy');
        $api->resource('users.contacts', 'ContactsController');
        $api->post('users/{id}/roles', 'UserRolesController@store');
        $api->delete('users/{id}/roles/{name}', 'UserRolesController@destroy');
        $api->post('users/{id}/permissions', 'UserPermissionsController@store');
        $api->delete('users/{id}/permissions', 'UserPermissionsController@destroy');
        $api->post('{recipient}/{id}/accolades', 'AccoladesController@store');
        $api->resource('groups', 'GroupsController');
        $api->get('groups/{id}/notes', 'GroupsController@notes');
        $api->post('groups/export', 'GroupsController@export');
        $api->post('groups/import', 'GroupsController@import');
        $api->resource('campaigns', 'CampaignsController', ['except' => ['show']]);
        $api->post('campaigns/export', 'CampaignsController@export');
        $api->post('campaigns/import', 'CampaignsController@import');
        $api->resource('trips', 'TripsController', ['except' => ['show']]);
        $api->post('trips/duplicate', 'TripsController@duplicate');
        $api->post('trips/export', 'TripsController@export');
        $api->post('trips/import', 'TripsController@import');
        $api->get('trips/{id}/todos', 'TripTodosController@index');
        $api->post('trips/{id}/todos', 'TripTodosController@store');
        $api->post('trips/{id}/register', 'TripRegistrationController@store');
        $api->post('trips/{id}/promo', 'TripsController@checkPromoCode');
        $api->post('interests/export', 'TripInterestsController@export');
        $api->resource('reservations', 'ReservationsController');
        $api->post('reservations/export', 'ReservationsController@export');
        $api->put('reservations/{id}/restore', 'ReservationsController@restore');
        $api->post('reservations/{id}/transfer', 'ReservationTransfersController@store');
        $api->resource('reservations.requirements', 'ReservationRequirementsController');
        $api->get('reservations/{reservations}/companions', 'CompanionsController@index');
        $api->post('reservations/{reservations}/companions', 'CompanionsController@store');
        $api->delete('reservations/{reservations}/companions', 'CompanionsController@destroy');
        $api->resource('fundraisers', 'FundraisersController');
        $api->get('fundraisers/{id}/donors', 'FundraisersController@donors');
        $api->get('fundraisers/{id}/donations', 'FundraisersController@donations');
        $api->resource('donors', 'DonorsController');
        $api->post('donors/export', 'DonorsController@export');
        $api->resource('donations', 'DonationsController');
        $api->post('donations/authorize', 'DonationsController@authorizeCard');
        $api->resource('passports', 'PassportsController');
        $api->post('passports/export', 'PassportsController@export');
        $api->post('passports/import', 'PassportsController@import');
        $api->resource('visas', 'VisasController');
        $api->resource('visas/export', 'VisasController@export');
        $api->resource('visas/import', 'VisasController@import');
        $api->resource('referrals', 'ReferralsController');
        $api->post('referrals/export', 'ReferralsController@export');
        $api->post('referrals/import', 'ReferralsController@import');
        $api->resource('campaigns.regions', 'RegionsController');

        $api->group(['namespace' => 'Teams'], function ($api) {
            $api->resource('teams/types', 'TeamTypesController');
            $api->resource('teams', 'TeamsController');
            $api->resource('{teamable_type}/{teamable_id}/teams', 'TeamablesController');
            $api->resource('teams.squads', 'TeamSquadsController');
            $api->resource('squads.members', 'SquadMembersController');
        });

        $api->resource('campaigns.transports', 'CampaignTransportsController');
        $api->resource('transports', 'TransportsController');
        $api->resource('transports.passengers', 'PassengersController');
        $api->resource('stories', 'StoriesController');
        $api->resource('funds', 'FundsController');
        $api->post('funds/export', 'FundsController@export');
        $api->put('funds/{id}/reconcile', 'FundsController@reconcile');
        $api->put('funds/{id}/restore', 'FundsController@restore');
        $api->get('funds/{id}/donors', 'FundDonorsController@index');
        $api->resource('transactions', 'TransactionsController');
        $api->post('transactions/export', 'TransactionsController@export');
        $api->resource('causes', 'ProjectCausesController');
        $api->post('causes/export', 'ProjectCausesController@export');
        $api->get('causes/{cause}/initiatives', 'ProjectInitiativesController@index');
        $api->resource('initiatives', 'ProjectInitiativesController', ['except' => 'index']);
        $api->post('initiatives/export', 'ProjectInitiativesController@export');
        $api->resource('projects', 'ProjectsController');
        $api->post('projects/export', 'ProjectsController@export');
        $api->resource('notes', 'NotesController');
        $api->resource('todos', 'TodosController');
        $api->resource('essays', 'EssaysController');
        $api->post('essays/export', 'EssaysController@export');
        $api->post('essays/import', 'EssaysController@import');
        $api->resource('influencers', 'EssaysController');
        $api->post('influencers/export', 'EssaysController@export');
        $api->post('influencers/import', 'EssaysController@import');
        $api->resource('costs', 'CostsController');
        $api->resource('costs.payments', 'CostPaymentsController');
        $api->resource('reservations.dues', 'ReservationDuesController');
        $api->resource('requirements', 'RequirementsController');
        $api->resource('requirements.conditions', 'RequirementConditionsController');
        $api->resource('deadlines', 'DeadlinesController');
        $api->resource('questionnaires', 'QuestionnairesController');
        $api->resource('permissions/roles', 'PermissionRolesController');
        $api->resource('permissions/abilities', 'PermissionAbilitiesController');
        $api->resource('promotionals', 'PromotionalsController');
        $api->put('promotionals/{id}/restore', 'PromotionalsController@restore');
        $api->resource('promocodes', 'PromocodesController');
        $api->put('promocodes/{id}/restore', 'PromocodesController@restore');
        $api->resource('accounting/classes', 'AccountingClassesController');
        $api->resource('accounting/items', 'AccountingItemsController');
        $api->resource('regions.accommodations', 'AccommodationsController');

        $api->group(['prefix' => 'rooming'], function ($api) {
            $api->resource('plans', 'Rooming\PlansController');
            $api->post('plans/export', 'Rooming\PlansController@export');
            $api->resource('types', 'Rooming\TypesController');
            $api->resource('plans.types', 'Rooming\PlanRoomTypesController');
            $api->resource('accommodations.types', 'Rooming\AccommodationRoomTypesController');
            $api->resource('rooms', 'Rooming\RoomsController');
            $api->resource('accommodations/{accommodationId}/rooms', 'Rooming\Accommodations\RoomsController');
            $api->resource('{roomableType}/{roomableId}/rooms', 'Rooming\Roomable\RoomsController');
            $api->resource('rooms/{roomId}/occupants', 'Rooming\RoomOccupantsController');
            $api->resource('{roomableType}/{roomableId}/plans', 'Rooming\Roomable\UtilizedPlansController');
        });

        $api->resource('activities', 'ActivitiesController');
        $api->resource('itineraries/travel', 'TravelItinerariesController');
        $api->resource('itineraries', 'ItinerariesController');
        $api->resource('transports', 'TransportsController');
        $api->resource('transports.activities', 'TransportActivitiesController');
        $api->resource('hubs', 'HubsController');
        $api->resource('hubs.activities', 'HubActivitiesController');

        $api->group(['prefix' => 'credentials'], function ($api) {
            $api->resource('medical', 'MedicalCredentialsController');
            $api->resource('media', 'MediaCredentialsController');
        });

        $api->group(['prefix' => 'medical'], function ($api) {
            $api->resource('releases', 'Medical\ReleasesController');
            $api->post('releases/export', 'Medical\ReleasesController@export');
            $api->post('releases/import', 'Medical\ReleasesController@import');
            $api->get('conditions', function () {
                return ['data' => \App\Models\v1\MedicalCondition::available()];
            });
            $api->get('allergies', function () {
                return ['data' => \App\Models\v1\MedicalAllergy::available()];
            });
        });

        $api->group(['prefix' => 'metrics'], function ($api) {
            $api->get('teams', 'Metrics\TeamsController@index');
        });

        $api->group(['prefix' => 'reports'], function ($api) {
            $api->post('reservations/{type}', 'Reporting\ReservationsController@store');
            $api->post('{type}/rooms', 'Reporting\RoomsController@store');
            $api->post('transports/{type}', 'Reporting\TransportsController@store');
        });

        /*
        |--------------------------------------------------------------------------
        | Mobile Specific Routes
        |--------------------------------------------------------------------------
        */
        $api->group(['prefix' => 'mobile'], function ($api) {
            $api->get('/', function () {
                return [
                    'message' => 'Welcome to the Missions.Me Mobile API',
                ];
            });

            $api->post('/login', 'Mobile\AuthenticationController@authenticate');
            $api->delete('/logout', 'Mobile\AuthenticationController@deauthenticate');
            $api->post('/refresh', 'Mobile\AuthenticationController@refresh');

            $api->get('/users/me', 'Mobile\UsersController@show');

            $api->group(['prefix' => 'interactions'], function ($api) {
                $api->resource('decisions', 'Interaction\DecisionsController');
                $api->resource('exams', 'Interaction\ExamsController');
                $api->resource('sites', 'Interaction\SitesController');
                $api->resource('stats', 'Interaction\StatsController');
            });
        });

        $api->post('/commands', function (Request $request) {

            $command = $request->get('command');
            $params = $request->get('parameters');

            $exitCode = Artisan::call($command, $params);

            return $exitCode;
        });
    });

    $api->group(['prefix' => 'utilities'], function ($api) {
        $api->get('team-roles/{type?}', 'UtilitiesController@getTeamRoles');
        $api->get('airlines/', 'AirlineController@index');
        $api->get('airlines/{id}', 'AirlineController@show');
        $api->get('airports/', 'AirportController@index');
        $api->get('airports/{iata}', 'AirportController@show');
        $api->get('countries', 'UtilitiesController@getCountries');
        $api->get('countries/{code}', 'UtilitiesController@getCountry');
        $api->get('timezones/{country_code?}', 'UtilitiesController@getTimezones');
        $api->get('past-trips', 'UtilitiesController@getPastTrips');
        $api->get('activities/types', 'UtilitiesController@getActivityTypes');
        $api->get('make-slug/{string}', function ($string) {
            return ['slug' => generate_slug($string)];
        });
        $api->get('make-fundraiser-slug/{string}', function ($string) {
            return ['slug' => generate_fundraiser_slug($string)];
        });
        $api->get('make-fund-slug/{string}', function ($string) {
            return ['slug' => generate_fundraiser_slug($string)];
        });
    });

    $api->get('images/{path}', 'UploadsController@display')->where('path', '.+');
    $api->get('groups', 'GroupsController@index');
    $api->get('groups/{group}', 'GroupsController@show');
    $api->get('stories', 'StoriesController@index');
    $api->get('{recipient}/{id}/accolades/{name}', 'AccoladesController@index');
    $api->get('trips', 'TripsController@index');
    $api->get('trips/{trip}', 'TripsController@show');
    $api->resource('fundraisers', 'FundraisersController');
    $api->resource('donations', 'DonationsController');
    $api->get('fundraisers/{id}/donors', 'FundraisersController@donors');
    $api->get('fundraisers/{id}/donations', 'FundraisersController@donations');
    $api->get('campaigns', 'CampaignsController@index');
    $api->resource('funds', 'FundsController'); // TODO restrict to get for queries like `funds/(recipient||general)`
    $api->get('causes', 'ProjectCausesController@index');



});
