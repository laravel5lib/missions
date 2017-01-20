<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'middleware' => 'api.throttle', 'limit' => 100, 'expires' => 1,
    'namespace' => 'App\Http\Controllers\Api'
], function($api)
{

    $api->get('/', function()
    {
        return [
            'message' => 'Welcome to the Missions.Me API',
        ];
    });

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
    $api->resource('users.contacts', 'ContactsController');
    $api->post('users/{id}/roles', 'UserRolesController@store');
    $api->delete('users/{id}/roles', 'UserRolesController@destroy');
    $api->post('users/{id}/abilities', 'UserAbilitiesController@store');
    $api->delete('users/{id}/abilities', 'UserAbilitiesController@destroy');
    $api->get('{recipient}/{id}/accolades/{name}', 'AccoladesController@index');
    $api->post('{recipient}/{id}/accolades', 'AccoladesController@store');
    $api->resource('groups', 'GroupsController');
    $api->get('groups/{id}/notes', 'GroupsController@notes');
    $api->post('groups/submit', 'GroupsController@submit');
    $api->resource('campaigns', 'CampaignsController');
    $api->post('campaigns/export', 'CampaignsController@export');
    $api->post('campaigns/import', 'CampaignsController@import');
    $api->resource('trips', 'TripsController');
    $api->post('trips/export', 'TripsController@export');
    $api->get('trips/{id}/todos', 'TripTodosController@index');
    $api->post('trips/{id}/todos', 'TripTodosController@store');
    $api->post('trips/{id}/register', 'TripsController@register');
    $api->resource('interests', 'TripInterestsController');
    $api->post('interests/export', 'TripInterestsController@export');
    $api->resource('reservations', 'ReservationsController');
    $api->post('reservations/export', 'ReservationsController@export');
    $api->resource('reservations.requirements', 'ReservationRequirementsController');
    $api->resource('assignments', 'AssignmentsController');
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
    $api->resource('referrals', 'ReferralsController');
    $api->post('referrals/export', 'ReferralsController@export');
    $api->resource('regions', 'RegionsController');
    $api->resource('teams', 'TeamsController');
    $api->resource('teams.members', 'TeamMembersController');
    $api->resource('transports', 'TransportsController');
    $api->resource('transports.passengers', 'PassengersController');
    $api->resource('accommodations', 'AccommodationsController');
    $api->resource('accommodations.occupants', 'OccupantsController');
    $api->resource('stories', 'StoriesController');
    $api->resource('funds', 'FundsController');
    $api->post('funds/export', 'FundsController@export');
    $api->put('funds/{id}/reconcile', 'FundsController@reconcile');
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
    $api->resource('costs', 'CostsController');
    $api->resource('costs.payments', 'CostPaymentsController');
    $api->resource('reservations.dues', 'ReservationDuesController');
    $api->resource('requirements', 'RequirementsController');
    $api->resource('deadlines', 'DeadlinesController');
    $api->resource('questionnaires', 'QuestionnairesController');
    $api->resource('permissions/roles', 'PermissionRolesController');
    $api->resource('permissions/abilities', 'PermissionAbilitiesController');

    $api->group(['prefix' => 'medical'], function($api)
    {
        $api->resource('releases', 'Medical\ReleasesController');
        $api->get('conditions', function() {
           return ['data' => \App\Models\v1\MedicalCondition::available()];
        });
        $api->get('allergies', function() {
            return ['data' => \App\Models\v1\MedicalAllergy::available()];
        });
    });

    $api->group(['prefix' => 'utilities'], function ($api) {
        $api->get('team-roles', 'UtilitiesController@getTeamRoles');
        $api->get('countries', 'UtilitiesController@getCountries');
        $api->get('countries/{code}', 'UtilitiesController@getCountry');
        $api->get('timezones', 'UtilitiesController@getTimezones');
        $api->get('past-trips', 'UtilitiesController@getPastTrips');
        $api->get('make-slug/{string}', function($string) {
            return ['slug' => generate_slug($string) ];
        });
        $api->get('make-fundraiser-slug/{string}', function($string) {
            return ['slug' => generate_fundraiser_slug($string) ];
        });
        $api->get('make-fund-slug/{string}', function($string) {
            return ['slug' => generate_fundraiser_slug($string) ];
        });
    });

    $api->post('contact', 'UtilitiesController@sendContactEmail');
    $api->post('speaker', 'UtilitiesController@sendSpeakerRequestEmail');
    $api->post('sponsor-project', 'UtilitiesController@sendProjectSponsorEmail');

    /*
    |--------------------------------------------------------------------------
    | Mobile Specific Routes
    |--------------------------------------------------------------------------
    */
    $api->group(['prefix' => 'mobile'], function ($api) {
        $api->get('/', function()
        {
            return [
                'message' => 'Welcome to the Missions.Me Mobile API',
            ];
        });

        $api->post('/login', 'Mobile\AuthenticationController@authenticate');
        $api->delete('/logout', 'Mobile\AuthenticationController@deauthenticate');
        $api->post('/refresh', 'Mobile\AuthenticationController@refresh');

        $api->get('/users/me', 'Mobile\UsersController@show');

        $api->group(['prefix' => 'interactions'], function($api)
        {
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
