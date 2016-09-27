<?php

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
//    'middleware' => 'api.throttle', 'limit' => 50, 'expires' => 1, DISABLE FOR DEVELOPMENT
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
    $api->post('/login', 'AuthenticationController@authenticate');
    $api->post('/register', 'AuthenticationController@register');
    $api->delete('/logout', 'AuthenticationController@deauthenticate');
    $api->post('/refresh', 'AuthenticationController@refresh');
    $api->get('/users/me', 'UsersController@show');
    $api->put('/users/me', 'UsersController@update');
    $api->resource('users', 'UsersController');
    $api->resource('users.contacts', 'ContactsController');
    $api->resource('groups', 'GroupsController');
    $api->get('groups/{id}/notes', 'GroupsController@notes');
    $api->post('groups/submit', 'GroupsController@submit');
    $api->resource('campaigns', 'CampaignsController');
    $api->resource('trips', 'TripsController');
    $api->post('trips/{id}/register', 'TripsController@register');
    $api->resource('reservations', 'ReservationsController');
    $api->put('reservations/{id}/payments/reconcile', 'ReservationsController@reconcile');
    $api->resource('assignments', 'AssignmentsController');
    $api->resource('fundraisers', 'FundraisersController');
    $api->get('fundraisers/{id}/donors', 'FundraisersController@donors');
    $api->get('fundraisers/{id}/donations', 'FundraisersController@donations');
    $api->resource('donors', 'DonorsController');
    $api->resource('donations', 'DonationsController');
    $api->post('donations/authorize', 'DonationsController@authorizeCard');
    $api->resource('passports', 'PassportsController');
    $api->resource('visas', 'VisasController');
    $api->resource('referrals', 'ReferralsController');
    $api->resource('regions', 'RegionsController');
    $api->resource('teams', 'TeamsController');
    $api->resource('teams.members', 'TeamMembersController');
    $api->resource('transports', 'TransportsController');
    $api->resource('transports.passengers', 'PassengersController');
    $api->resource('accommodations', 'AccommodationsController');
    $api->resource('accommodations.occupants', 'OccupantsController');
    $api->resource('stories', 'StoriesController');
    $api->get('funds', 'FundsController@index');
    $api->get('funds/{id}', 'FundsController@show');
    $api->resource('transactions', 'TransactionsController');

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
        $api->get('countries', 'UtilitiesController@getCountries');
        $api->get('countries/{$code}', 'UtilitiesController@getCountry');
        $api->get('timezones', 'UtilitiesController@getTimezones');
    });

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
});
