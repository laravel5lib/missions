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

Route::resource('trips', 'TripsController');

Route::resource('groups', 'GroupsController');

Route::resource('reservations', 'ReservationsController');

Route::resource('users', 'UsersController');

