<?php

use App\Models\v1\Fundraiser;
use App\Models\v1\Representative;
use Artesaos\SEOTools\Facades\SEOMeta;

/*
|--------------------------------------------------------------------------
| Admin Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', 'AdminController@index')->middleware(['can:access_backend']);

Route::view('tags/{type}', 'admin.tags.index');

Route::view('costs', 'admin.costs.index');

Route::get('users/stop', 'UsersController@stopImpersonate');
Route::get('users/{id}/impersonate', 'UsersController@impersonate');

Route::prefix('campaigns')->group(function () {
    Route::get('/', 'CampaignsController@index');
    Route::get('create', 'CampaignsController@create');
    Route::get('{id}/edit', 'CampaignsController@edit');
    Route::get('{id}/trips/create', 'TripsController@create');
    // Reservations
    Route::get('{campaignId}/reservations/missionaries', 'ReservationsController@index');
    Route::get('{campaignId}/reservations/dropped', 'ReservationsController@dropped');
    // Flights
    Route::get('{campaignId}/reservations/flights', 'FlightController@index');
    Route::get('{campaign}/itineraries/{itinerary}', 'FlightItineraryController@show');
    Route::get('{campaign}/itineraries/{itinerary}/edit', 'FlightItineraryController@edit');
    // Squads
    Route::get('{campaignId}/reservations/squads', 'SquadController@index');
    Route::get('{campaignId}/reservations/squads/create', 'SquadController@create');
    Route::get('{campaignId}/reservations/squads/{squad}', 'SquadController@show');
    Route::get('{campaignId}/reservations/squads/{squad}/edit', 'SquadController@edit');
    // Regions
    Route::get('{campaignId}/regions/create', 'RegionController@create');
    Route::get('{campaignId}/regions/{region}', 'RegionController@show');
    Route::get('{campaignId}/regions/{region}/edit', 'RegionController@edit');
    // Interests
    Route::resource('{campaignId}/reservations/interests', 'TripInterestsController');
    // Campaign details
    Route::get('{id}/{tab?}', 'CampaignsController@show');
    Route::get('{id}/costs/{cost}', 'CampaignCostController@show');
    Route::get('{id}/requirements/create', 'CampaignRequirementController@create');
    Route::get('{id}/requirements/{requirement}', 'CampaignRequirementController@show');
    Route::get('{id}/requirements/{requirement}/edit', 'CampaignRequirementController@edit');
});
Route::get('campaign-groups/{group}/edit', 'CampaignGroupController@edit');
Route::get('campaign-groups/{group}/{tab?}', 'CampaignGroupController@show');
Route::get('campaign-groups/{group}/prices/{price}', 'CampaignGroupPriceController@show');
Route::get('campaign-groups/{group}/requirements/create', 'CampaignGroupRequirementController@create');
Route::get('campaign-groups/{group}/requirements/{requirement}', 'CampaignGroupRequirementController@show');
Route::get('campaign-groups/{group}/requirements/{requirement}/edit', 'CampaignGroupRequirementController@edit');
Route::get('campaign-groups/{group}/trips/create', 'CampaignGroupTripController@create');

Route::get('flights/{flight}', 'FlightController@show');
Route::get('flights/{flight}/edit', 'FlightController@edit');

Route::get('trips/{id}/{tab?}', 'TripsController@show');
Route::get('trips/{id}/prices/{price}', 'TripPriceController@show');
Route::get('trips/{id}/requirements/create', 'TripRequirementController@create');
Route::get('trips/{id}/requirements/{requirement}', 'TripRequirementController@show');
Route::get('trips/{id}/requirements/{requirement}/edit', 'TripRequirementController@edit');
Route::get('/trips/{id}/reservations/create', 'ReservationsController@create');
Route::resource('trips', 'TripsController');

Route::resource('organizations', 'GroupsController');

Route::prefix('reservations')->group(function () {
    Route::get('create', 'ReservationsController@create');
    Route::get('{id}/edit', 'ReservationsController@edit');
    Route::get('{id}/transfer', 'ReservationsController@transfer');
    Route::get('{id}/{tab?}', 'ReservationsController@show');
    Route::get('{id}/prices/{price}', 'ReservationPriceController@show');
    Route::get('{id}/requirements/create', 'ReservationRequirementController@create');
    Route::get('{id}/requirements/{requirement}', 'ReservationRequirementController@show');
    Route::get('{id}/requirements/{requirement}/edit', 'ReservationRequirementController@edit');
});

Route::get('/funds/{fund}/fundraisers/create', '\App\Http\Controllers\Web\FundraisersController@create');
Route::get('/fundraisers/{fundraiser}/edit', '\App\Http\Controllers\Web\FundraisersController@edit');
Route::get('/fundraisers/{fundraiser}/updates', function(Fundraiser $fundraiser) {
    return view('site.fundraisers.updates', compact('fundraiser'));
});
Route::get('/fundraisers/{fundraiser}/donations', function(Fundraiser $fundraiser) {
    return view('site.fundraisers.donations', compact('fundraiser'));
});
Route::get('/fundraisers/{fundraiser}/sharing', function(Fundraiser $fundraiser) {
    return view('site.fundraisers.sharing', compact('fundraiser'));
});

Route::resource('users', 'UsersController');
Route::get('users/{id}/{tab?}', 'UsersController@show')->where('tab', 'details|permissions');

// Representatives
Route::get('representatives', function () {
    return view('admin.representatives.index');
})->middleware('can:view,App\Models\v1\Representative');

Route::get('representatives/{representative}', function (Representative $representative) {
    return view('admin.representatives.edit', compact('representative'));
})->middleware('can:update,App\Models\v1\Representative');

Route::resource('uploads', 'UploadsController');
Route::resource('donors', 'DonorsController');
Route::resource('funds', 'FundsController');
Route::resource('transactions', 'TransactionsController');

Route::prefix('records')->group(function () {
    Route::get('{tab?}', 'DocumentController@index');
    Route::get('{tab}/create', 'DocumentController@create');
    Route::get('{tab}/{id}', 'DocumentController@show');
    Route::get('{tab}/{id}/edit', 'DocumentController@edit');
});

Route::get('reports', function () {
    SEOMeta::setTitle('Reports');
    return view('admin.reports.index');
})->middleware('can:view,App\Models\v1\Report');

Route::get('leads', function () {
    SEOMeta::setTitle('Leads');
    return view('admin.leads.index');
})->middleware('can:view,App\Models\v1\Lead');

Route::get('leads/{lead}', function ($lead) {
    SEOMeta::setTitle('Lead Details');
    return view('admin.leads.show', compact('lead'));
})->middleware('can:view,App\Models\v1\Lead');
