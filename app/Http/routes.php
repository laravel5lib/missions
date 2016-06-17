<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
$dispatcher = app('Dingo\Api\Dispatcher');

Route::get('/admin/users', function () use ($dispatcher) {
    Auth::loginUsingId('fc4b1442-3a03-4339-86e2-6ecbfc0e3e30');
    try {
        $users = $dispatcher->be(auth()->user())->get('users');
    } catch (Dingo\Api\Exception\InternalHttpException $e) {
        // We can get the response here to check the status code of the error or response body.
        $response = $e->getResponse();

        return $response;
    }

    return View::make('admin.users')->with('users', $users);
});

Route::get('/admin', function () {
    return view('admin.index');
});

Route::get('/dashboard', function () {
    return view('dashboard.index');
});

Route::get('/campaigns', function () {
    return view('site.campaigns.index');
});

Route::get('/campaigns/{slug}', function ($slug = null) use ($dispatcher) {
    try {
        $campaign = $dispatcher->get('campaigns/' . $slug);
    } catch (Dingo\Api\Exception\InternalHttpException $e) {
        // We can get the response here to check the status code of the error or response body.
        $response = $e->getResponse();

        return $response;
    }
    return view('site.campaigns.show', compact('campaign'));
});

Route::get('/trips', function () use ($dispatcher) {
    try {
        $trips = $dispatcher->get('trips', ['include' => 'campaign']);
    } catch (Dingo\Api\Exception\InternalHttpException $e) {
        // We can get the response here to check the status code of the error or response body.
        $response = $e->getResponse();

        return $response;
    }
    return view('site.trips.index')->with('trips', $trips);
});

Route::get('/trips/{id}', function ($id = null) use ($dispatcher) {
    try {
        $trip = $dispatcher->get('trips/'. $id, ['include' => 'campaign,costs.payments,requirements,notes,deadlines']);
    } catch (Dingo\Api\Exception\InternalHttpException $e) {
        // We can get the response here to check the status code of the error or response body.
        $response = $e->getResponse();

        return $response;
    }
    return view('site.trips.show')->with('trip', $trip);
});

Route::get('/', function () {
    return view('site.index');
});
