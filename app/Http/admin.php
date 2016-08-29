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

Route::resource('uploads', 'UploadsController');

Route::get('/donors', function() use($dispatcher) {
    $donors = $dispatcher->get('donors');
    $donors->setPath('/admin/donors');
    return view('admin.financials.donors.index', compact('donors'));
});

Route::get('/funds', function() use($dispatcher) {
    $funds = $dispatcher->get('funds');
    $funds->setPath('/admin/funds');
    return view('admin.financials.funds.index', compact('funds'));
});

Route::get('/transactions', function() use($dispatcher) {
    $transactions = $dispatcher->get('transactions');
    $transactions->setPath('/admin/transactions');
    return view('admin.financials.transactions.index', compact('transactions'));
});
