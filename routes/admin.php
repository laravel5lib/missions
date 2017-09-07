<?php

/*
|--------------------------------------------------------------------------
| Admin Web Routes
|--------------------------------------------------------------------------
*/

$dispatcher = app('Dingo\Api\Dispatcher');

$this->get('/', function () {
    return view('admin.index');
})->middleware(['can:access_backend']);

$this->get('users/stop', 'UsersController@stopImpersonate');
$this->get('users/{id}/impersonate', 'UsersController@impersonate');

$this->get('campaigns', 'CampaignsController@index');
$this->get('campaigns/create', 'CampaignsController@create');
$this->get('campaigns/{id}/edit', 'CampaignsController@edit');
$this->get('campaigns/{id}/{tab?}', 'CampaignsController@show');
$this->get('campaigns/{id}/{tab?}/{tabId}', 'CampaignsController@show');

$this->get('trips/{id}/{tab?}', 'TripsController@show');
$this->resource('trips', 'TripsController');

$this->resource('groups', 'GroupsController');
$this->resource('interests', 'TripInterestsController');

$this->get('reservations/{tab?}', 'ReservationsController@index')->where('tab', 'current|archived|dropped|prospects');
$this->get('reservations/create', 'ReservationsController@create');
$this->get('reservations/{id}/edit', 'ReservationsController@edit');
$this->get('reservations/{id}/{tab?}', 'ReservationsController@show');

$this->get('causes/{cause_id}/projects/create', 'ProjectsController@create');
$this->get('causes/{cause_id}/initiatives/create', 'ProjectInitiativesController@create');
$this->get('causes/{id}/{tab?}', 'ProjectCausesController@show')->where('tab', '.+');
$this->resource('causes', 'ProjectCausesController');
$this->get('initiatives/{id}', 'ProjectInitiativesController@show');
$this->get('projects/{id}/{tab?}', 'ProjectsController@show');

$this->resource('users', 'UsersController');

$this->resource('uploads', 'UploadsController');

$this->resource('donors', 'DonorsController');

$this->resource('funds', 'FundsController');

$this->resource('transactions', 'TransactionsController');

$this->get('records/{tab?}', function ($tab = 'passports') {
    return view('admin.records.'.$tab.'.index', compact('tab'));
});

$this->get('records/passports/create', function () {
    return view('admin.records.passports.create');
});

$this->get('records/passports/{id}', function ($id) use ($dispatcher) {
    $passport = $dispatcher->get('passports/' . $id);
    return view('admin.records.passports.show', compact('passport'));
});

$this->get('records/passports/{id}/edit', function ($id) {
    return view('admin.records.passports.edit', compact('id'));
});

$this->get('records/visas/create', function () {
    return view('admin.records.visas.create');
});

$this->get('records/visas/{id}', function ($id) use ($dispatcher) {
    $visa = $dispatcher->get('visas/' . $id);
    return view('admin.records.visas.show', compact('visa'));
});

$this->get('records/visas/{id}/edit', function ($id) {
    return view('admin.records.visas.edit', compact('id'));
});

$this->get('records/medical-releases/create', function () {
    return view('admin.records.medical-releases.create');
});

$this->get('records/medical-releases/{id}', function ($id) use ($dispatcher) {
    $release = $dispatcher->get('medical/releases/' . $id);
    return view('admin.records.medical-releases.show', compact('release'));
});

$this->get('records/medical-releases/{id}/edit', function ($id) {
    return view('admin.records.medical-releases.edit', compact('id'));
});

$this->get('records/essays/create', function () {
    return view('admin.records.essays.create');
});

$this->get('records/essays/{id}', function ($id) use ($dispatcher) {
    $essay = $dispatcher->get('essays/' . $id);
    return view('admin.records.essays.show', compact('essay'));
});

$this->get('records/essays/{id}/edit', function ($id) {
    return view('admin.records.essays.edit', compact('id'));
});

$this->get('records/referrals/create', function () {
    return view('admin.records.referrals.create');
});

$this->get('records/referrals/{id}', function ($id) use ($dispatcher) {
    $referral = $dispatcher->get('referrals/' . $id);
    return view('admin.records.referrals.show', compact('referral'));
});

$this->get('records/referrals/{id}/edit', function ($id) {
    return view('admin.records.referrals.edit', compact('id'));
});

$this->get('reports', function () {
    return view('admin.reports.index');
})->middleware('can:view,App\Models\v1\Report');

$this->resource('records/medical-credentials', 'MedicalCredentialsController', [
    'except' => ['index', 'destroy']
]);

$this->resource('records/media-credentials', 'MediaCredentialsController', [
    'except' => ['index', 'destroy']
]);

$this->resource('records/influencers', 'InfluencersController', [
    'except' => ['index', 'destroy']
]);
