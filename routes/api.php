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
// Requirements
Route::apiResource('{requireableType}/{requireableId}/requirements', 'RequirementsController');
Route::post('campaigns/{campaign}/requirements/{requirement}/add', 'BulkAddCampaignRequirementController');
Route::post('campaign-groups/{campaignGroup}/requirements/{requirement}/add', 'BulkAddCampaignGroupRequirementController');
Route::post('trips/{trip}/requirements/{requirement}/add', 'BulkAddTripRequirementController');

// Campaigns
Route::apiResource('campaigns/{campaignId}/trip-templates', 'TripTemplateController');
Route::apiResource('campaigns/{campaignId}/groups', 'CampaignGroupController');
Route::apiResource('campaigns/{campaignId}/costs', 'CampaignCostController')->middleware('auth:api');
Route::post('campaign-groups/{groupId}/prices/{priceId}/push', 'CampaignGroupPriceableController@store');
Route::apiResource('campaign-groups/{groupId}/prices', 'CampaignGroupPriceController');
Route::get('campaigns/{campaignId}/groups/{groupId}/prices', function($campaignId, $groupId) {
    $group = \App\Models\v1\CampaignGroup::whereCampaignId($campaignId)->whereGroupId($groupId)->firstOrFail();
    return redirect('/api/campaign-groups/'.$group->uuid.'/prices');
});

// Trips
Route::post('trips/{tripId}/prices/{priceId}/push', 'TripPriceableController@store');
Route::apiResource('trips/{tripId}/prices', 'TripPriceController');

// Reservations
Route::post('reservations/{reservationId}/prices/lock', 'ReservationPriceLockController@store');
Route::delete('reservations/{reservationId}/prices/lock', 'ReservationPriceLockController@destroy');
Route::apiResource('reservations/{reservationId}/prices', 'ReservationPriceController');
Route::post('reservations/{id}/transfer', 'ReservationTransfersController@store');
Route::get('reservations/{reservationId}/{documentType}', 'ReservationDocumentController@index');
Route::post('reservations/{reservationId}/{documentType}', 'ReservationDocumentController@store');
Route::delete('reservations/{reservationId}/{documentType}/{documentId}', 'ReservationDocumentController@destroy');

// Companions
Route::get('companions', 'CompanionController@index');

// Leads
Route::apiResource('leads', 'LeadController');

// Flights
Route::apiResource('campaigns/{campaignId}/flights/segments', 'FlightSegmentController');
Route::apiResource('campaigns/{campaignId}/flights/itineraries', 'FlightItineraryController');
Route::get('campaigns/{campaignId}/flights/passengers', 'FlightPassengerController@index');
Route::patch('campaigns/{campaignId}/flights/passengers', 'FlightPassengerController@update');
Route::put('flights/itineraries/published', 'FlightItineraryPublicationController@update');
Route::apiResource('flights', 'FlightController');

// Transactions
Route::apiResource('transactions', 'TransactionsController');

// Reports
Route::get('reports/create/{type}/{format}', 'UserReportsController@create')->middleware('auth:api');

// Squads
Route::apiResource('regions', 'RegionController');
Route::put('squads/published', 'SquadPublicationController@update');
Route::apiResource('squads', 'SquadController');
Route::apiResource('squad-members', 'SquadMemberController');

// Medical Releases
Route::apiResource('medical-releases', 'MedicalReleaseController');

// Passports
Route::apiResource('passports', 'PassportsController');

// Visas
Route::apiResource('visas', 'VisasController');

// Referrals
Route::apiResource('referrals', 'ReferralsController');

// Essays
Route::apiResource('essays', 'EssaysController');
Route::apiResource('influencer-applications', 'EssaysController');

// Questionnaires
Route::apiResource('questionnaires', 'QuestionnairesController');
Route::apiResource('airport-preferences', 'QuestionnairesController');
Route::apiResource('arrival-designations', 'QuestionnairesController');
Route::apiResource('minor-releases', 'QuestionnairesController');

// Credentials
Route::apiResource('medical-credentials', 'MedicalCredentialsController');
Route::apiResource('media-credentials', 'MediaCredentialsController');

// Tags
Route::get('tags/{type?}', 'TagController@index');
Route::post('tags/{type}', 'TagController@store');
Route::put('tags/{type}/{tag}', 'TagController@update');
Route::patch('tags/{type}/{tag}', 'TagController@update');
Route::delete('/tags/{tag}', 'TagController@destroy');

// Dingo API routes
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

    $api->post('fundraisers/{id}/remind', 'FundraiserReminderController@store');

    $api->group(['middleware' => ['api.auth']], function ($api) {

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
        $api->resource('trips', 'TripsController', ['except' => ['show']]);
        $api->post('trips/export', 'TripsController@export');
        $api->post('trips/import', 'TripsController@import');
        $api->get('trips/{id}/todos', 'TripTodosController@index');
        $api->post('trips/{id}/todos', 'TripTodosController@store');
        $api->post('trips/{id}/register', 'TripRegistrationController@store');
        $api->post('trips/{id}/promo', 'TripsController@checkPromoCode');
        $api->post('interests/export', 'TripInterestsController@export');
        $api->resource('reservations', 'ReservationsController');
        $api->put('reservations/{id}/restore', 'ReservationsController@restore');
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
        $api->resource('causes', 'ProjectCausesController');
        $api->post('causes/export', 'ProjectCausesController@export');
        $api->get('causes/{cause}/initiatives', 'ProjectInitiativesController@index');
        $api->resource('initiatives', 'ProjectInitiativesController', ['except' => 'index']);
        $api->post('initiatives/export', 'ProjectInitiativesController@export');
        $api->resource('projects', 'ProjectsController');
        $api->post('projects/export', 'ProjectsController@export');
        $api->resource('notes', 'NotesController');
        $api->resource('todos', 'TodosController');
        $api->resource('deadlines', 'DeadlinesController');
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

        $api->group(['prefix' => 'medical'], function ($api) {
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
    $api->post('fundraisers/{uuid}/media', 'FundraiserMediaController@store');
    $api->post('fundraisers/media/delete', 'FundraiserMediaController@destroy');
    $api->resource('donations', 'DonationsController');
    $api->get('fundraisers/{id}/donors', 'FundraisersController@donors');
    $api->get('fundraisers/{id}/donations', 'FundraisersController@donations');
    $api->get('campaigns', 'CampaignsController@index');
    $api->resource('funds', 'FundsController'); // TODO restrict to get for queries like `funds/(recipient||general)`
    $api->get('causes', 'ProjectCausesController@index');
});
