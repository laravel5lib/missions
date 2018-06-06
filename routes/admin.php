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

Route::view('costs', 'admin.costs.index');

Route::get('users/stop', 'UsersController@stopImpersonate');
Route::get('users/{id}/impersonate', 'UsersController@impersonate');

Route::prefix('campaigns')->group(function () {
    Route::get('/', 'CampaignsController@index');
    Route::get('create', 'CampaignsController@create');
    Route::get('{id}/edit', 'CampaignsController@edit');
    Route::get('{id}/trips/create', 'TripsController@create');
    Route::get('{campaignId}/reservations/{tab?}', 'ReservationsController@index')
        ->where('tab', 'current|archived|dropped|prospects');
    Route::get('{campaignId}/flights', 'FlightController@index');
    Route::get('{id}/{tab?}', 'CampaignsController@show');
    Route::get('{id}/costs/{cost}', 'CampaignCostController@show');
});
Route::get('campaign-groups/{group}/edit', 'CampaignGroupController@edit');
Route::get('campaign-groups/{group}/{tab?}', 'CampaignGroupController@show');
Route::get('campaign-groups/{group}/prices/{price}', 'CampaignGroupPriceController@show');
Route::get('campaign-groups/{group}/trips/create', 'CampaignGroupTripController@create');

Route::get('flights/{flight}', 'FlightController@show');

Route::get('trips/{id}/{tab?}', 'TripsController@show');
Route::get('trips/{id}/prices/{price}', 'TripPriceController@show');
Route::get('/trips/{id}/reservations/create', 'ReservationsController@create');
Route::resource('trips', 'TripsController');

Route::resource('organizations', 'GroupsController');
Route::resource('interests', 'TripInterestsController');

Route::prefix('reservations')->group(function () {
    Route::get('create', 'ReservationsController@create');
    Route::get('{id}/edit', 'ReservationsController@edit');
    Route::get('{id}/{tab?}', 'ReservationsController@show');
    Route::get('{id}/prices/{price}', 'ReservationPriceController@show');
    Route::get('{id}/transfer', 'ReservationsController@transfer');
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

Route::get('causes/{cause_id}/projects/create', 'ProjectsController@create');
Route::get('causes/{cause_id}/initiatives/create', 'ProjectInitiativesController@create');
Route::get('causes/{id}/{tab?}', 'ProjectCausesController@show')->where('tab', '.+');
Route::resource('causes', 'ProjectCausesController');
Route::get('initiatives/{id}', 'ProjectInitiativesController@show');
Route::get('projects/{id}/{tab?}', 'ProjectsController@show');

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
    Route::get('{tab?}', function ($tab = 'passports') {
        $title = title_case(str_replace("-", " ", $tab));
        SEOMeta::setTitle($title);

        return view('admin.records.'.$tab.'.index', compact('tab'));
    });

    Route::resource('passports', 'PassportsController', [
        'only' => ['create', 'show', 'edit']
    ]);

    Route::resource('visas', 'VisasController', [
        'only' => ['create', 'show', 'edit']
    ]);

    Route::resource('essays', 'EssaysController', [
        'only' => ['create', 'show', 'edit']
    ]);

    Route::resource('medical-releases', 'MedicalReleasesController', [
        'only' => ['create', 'show', 'edit']
    ]);

    Route::resource('referrals', 'ReferralsController', [
        'only' => ['create', 'show', 'edit']
    ]);

    Route::resource('medical-credentials', 'MedicalCredentialsController', [
        'only' => ['create', 'show', 'edit']
    ]);

    Route::resource('media-credentials', 'MediaCredentialsController', [
        'only' => ['create', 'show', 'edit']
    ]);

    Route::resource('influencers', 'InfluencersController', [
        'only' => ['create', 'show', 'edit']
    ]);
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
