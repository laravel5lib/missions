<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

$dispatcher = app('Dingo\Api\Dispatcher');

Route::get('/', function () {
    return view('admin.index');
});

Route::resource('campaigns', 'CampaignsController');
Route::resource('campaigns.trips', 'TripsController');
Route::resource('campaigns.regions', 'RegionsController');
Route::resource('campaigns.transports', 'TransportsController');

Route::get('trips/{id}/{tab?}', 'TripsController@show');
Route::resource('trips', 'TripsController');

Route::resource('groups', 'GroupsController');
Route::resource('interests', 'TripInterestsController');

Route::get('reservations/{tab?}', 'ReservationsController@index')->where('tab', 'current|archived|dropped|prospects');
Route::resource('reservations', 'ReservationsController');

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

Route::get('records/{tab?}', function($tab = 'passports') {
    return view('admin.records.'.$tab.'.index', compact('tab'));
});

Route::get('records/passports/create', function () {
    return view('admin.records.passports.create');
});

Route::get('records/passports/{id}', function ($id) use ($dispatcher) {
    $passport = $dispatcher->get('passports/' . $id);
    return view('admin.records.passports.show', compact('passport'));
});

Route::get('records/passports/{id}/edit', function ($id) {
    return view('admin.records.passports.edit', compact('id'));
});
