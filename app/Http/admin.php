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

Route::get('users/stop', 'UsersController@stopImpersonate');
Route::get('users/{id}/impersonate', 'UsersController@impersonate');

Route::get('campaigns', 'CampaignsController@index');
Route::get('campaigns/create', 'CampaignsController@create');
Route::get('campaigns/{id}/edit', 'CampaignsController@edit');
Route::get('campaigns/{id}/{tab?}', 'CampaignsController@show');
Route::get('campaigns/{id}/{tab?}/{tabId}', 'CampaignsController@show');

Route::get('trips/{id}/{tab?}', 'TripsController@show');
Route::resource('trips', 'TripsController');

Route::resource('groups', 'GroupsController');
Route::resource('interests', 'TripInterestsController');

Route::get('reservations/{tab?}', 'ReservationsController@index')->where('tab', 'current|archived|dropped|prospects');
Route::get('reservations/create', 'ReservationsController@create');
Route::get('reservations/{id}/edit', 'ReservationsController@edit');
Route::get('reservations/{id}/{tab?}', 'ReservationsController@show');

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

Route::get('records/{tab?}', function ($tab = 'passports') {
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

Route::get('records/visas/create', function () {
    return view('admin.records.visas.create');
});

Route::get('records/visas/{id}', function ($id) use ($dispatcher) {
    $visa = $dispatcher->get('visas/' . $id);
    return view('admin.records.visas.show', compact('visa'));
});

Route::get('records/visas/{id}/edit', function ($id) {
    return view('admin.records.visas.edit', compact('id'));
});

Route::get('records/medical-releases/create', function () {
    return view('admin.records.medical-releases.create');
});

Route::get('records/medical-releases/{id}', function ($id) use ($dispatcher) {
    $release = $dispatcher->get('medical/releases/' . $id);
    return view('admin.records.medical-releases.show', compact('release'));
});

Route::get('records/medical-releases/{id}/edit', function ($id) {
    return view('admin.records.medical-releases.edit', compact('id'));
});

Route::get('records/essays/create', function () {
    return view('admin.records.essays.create');
});

Route::get('records/essays/{id}', function ($id) use ($dispatcher) {
    $essay = $dispatcher->get('essays/' . $id);
    return view('admin.records.essays.show', compact('essay'));
});

Route::get('records/essays/{id}/edit', function ($id) {
    return view('admin.records.essays.edit', compact('id'));
});

Route::get('records/referrals/create', function () {
    return view('admin.records.referrals.create');
});

Route::get('records/referrals/{id}', function ($id) use ($dispatcher) {
    $referral = $dispatcher->get('referrals/' . $id);
    return view('admin.records.referrals.show', compact('referral'));
});

Route::get('records/referrals/{id}/edit', function ($id) {
    return view('admin.records.referrals.edit', compact('id'));
});

Route::get('reports', function () {
    return view('admin.reports.index');
});

$this->resource('records/medical-credentials', 'MedicalCredentialsController', [
    'except' => ['index', 'destroy']
]);

$this->resource('records/media-credentials', 'MediaCredentialsController', [
    'except' => ['index', 'destroy']
]);

$this->resource('records/influencers', 'InfluencersController', [
    'except' => ['index', 'destroy']
]);
