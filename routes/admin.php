<?php

/*
|--------------------------------------------------------------------------
| Admin Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('admin.index');
})->middleware(['can:access_backend']);

Route::get('users/stop', 'UsersController@stopImpersonate');
Route::get('users/{id}/impersonate', 'UsersController@impersonate');

Route::prefix('campaigns')->group(function () {
    Route::get('/', 'CampaignsController@index');
    Route::get('create', 'CampaignsController@create');
    Route::get('{id}/edit', 'CampaignsController@edit');
    Route::get('{id}/{tab?}', 'CampaignsController@show');
    Route::get('{id}/{tab?}/{tabId}', 'CampaignsController@show');
});

Route::get('trips/{id}/{tab?}', 'TripsController@show');
Route::resource('trips', 'TripsController');

Route::resource('groups', 'GroupsController');
Route::resource('interests', 'TripInterestsController');

Route::prefix('reservations')->group(function () {
    Route::get('{tab?}', 'ReservationsController@index')
        ->where('tab', 'current|archived|dropped|prospects');
    Route::get('create', 'ReservationsController@create');
    Route::get('{id}/edit', 'ReservationsController@edit');
    Route::get('{id}/{tab?}', 'ReservationsController@show');
});

Route::get('causes/{cause_id}/projects/create', 'ProjectsController@create');
Route::get('causes/{cause_id}/initiatives/create', 'ProjectInitiativesController@create');
Route::get('causes/{id}/{tab?}', 'ProjectCausesController@show')->where('tab', '.+');
Route::resource('causes', 'ProjectCausesController');
Route::get('initiatives/{id}', 'ProjectInitiativesController@show');
Route::get('projects/{id}/{tab?}', 'ProjectsController@show');

Route::resource('users', 'UsersController');
Route::resource('uploads', 'UploadsController');
Route::resource('donors', 'DonorsController');
Route::resource('funds', 'FundsController');
Route::resource('transactions', 'TransactionsController');

Route::prefix('records')->group(function () {
    Route::get('{tab?}', function ($tab = 'passports') {
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
    return view('admin.reports.index');
})->middleware('can:view,App\Models\v1\Report');
