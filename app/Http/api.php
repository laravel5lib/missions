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

    $api->post('/upload', function(Dingo\Api\Contract\Http\Request $request)
    {
        $file = $request->file('image');
        $path = $request->get('path');

        $img = Intervention\Image\Facades\Image::make($file)->resize(500, 500);
        $img = $img->stream();

        Illuminate\Support\Facades\Storage::disk('s3')->put(
            $path.'/'.time().'.'.$file->getClientOriginalExtension(),
            $img->__toString()
        );
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
    $api->resource('campaigns', 'CampaignsController');
    $api->resource('trips', 'TripsController');
    $api->resource('reservations', 'ReservationsController');
    $api->get('reservations/{id}/donations', 'ReservationsController@donations');
    $api->resource('assignments', 'AssignmentsController');
    $api->get('fundraisers.donations', 'DonationsController@index');
    $api->resource('fundraisers', 'FundraisersController');
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

    $api->group(['prefix' => 'medical'], function($api)
    {
        $api->resource('releases', 'Medical\ReleasesController');
    });

    $api->group(['prefix' => 'interactions'], function($api)
    {
        $api->resource('decisions', 'Interaction\DecisionsController');
        $api->resource('exams', 'Interaction\ExamsController');
        $api->resource('sites', 'Interaction\SitesController');
        $api->resource('stats', 'Interaction\StatsController');
    });

    $api->group(['prefix' => 'utilities'], function ($api) {
        $api->get('countries', 'UtilitiesController@getCountries');
        $api->get('countries/{$code}', 'UtilitiesController@getCountry');
        $api->get('timezones', 'UtilitiesController@getTimezones');
    });
});
