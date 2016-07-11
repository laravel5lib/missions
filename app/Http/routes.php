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

use Illuminate\Http\Request;

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

Route::get('/dashboard', function () {
    return view('dashboard.index');
});

Route::get('/dashboard/reservations', function () use ($dispatcher) {
    //Auth::loginUsingId(Auth::user()->id);
    Auth::loginUsingId('39edac0c-51dc-48af-a631-983659a6a630');

    try {
        $user = $dispatcher->be(auth()->user())->get('users/me', ['include' => 'reservations.trip.campaign,group' ]);
    } catch (Dingo\Api\Exception\InternalHttpException $e) {
        // We can get the response here to check the status code of the error or response body.
        $response = $e->getResponse();
        return $response;
    }

//    return $reservations;
    return view('dashboard.reservations.index', compact('user'));
});

Route::get('/dashboard/reservations/{id}', function ($id, Request $request) use ($dispatcher) {
    try {
        $reservation = $dispatcher->get('reservations/' . $id, ['include' => 'trip.campaign,trip.group']);
    } catch (Dingo\Api\Exception\InternalHttpException $e) {
        // We can get the response here to check the status code of the error or response body.
        $response = $e->getResponse();

        return $response;
    }
    $active = 'details';
    return view('dashboard.reservations.show', compact('reservation', 'active'));
});

Route::get('/dashboard/reservations/{id}/requirements', function ($id, Request $request) use ($dispatcher) {
    try {
        $reservation = $dispatcher->get('reservations/' . $id, ['include' => 'trip.campaign,requirements']);
    } catch (Dingo\Api\Exception\InternalHttpException $e) {
        // We can get the response here to check the status code of the error or response body.
        $response = $e->getResponse();

        return $response;
    }
    $active = $request->segment(4);
    return view('dashboard.reservations.requirements', compact('reservation', 'active'));
});

Route::get('/dashboard/reservations/{id}/fundraisers', function ($id, Request $request) use ($dispatcher) {
    try {
        $reservation = $dispatcher->get('reservations/' . $id, ['include' => 'trip.campaign,fundraisers']);
    } catch (Dingo\Api\Exception\InternalHttpException $e) {
        // We can get the response here to check the status code of the error or response body.
        $response = $e->getResponse();

        return $response;
    }
    $active = $request->segment(4);
    return view('dashboard.reservations.fundraisers', compact('reservation', 'active'));
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

Route::get('/trips/{id}/register', function ($id = null) use ($dispatcher) {
    try {
        $trip = $dispatcher->get('trips/'. $id);
    } catch (Dingo\Api\Exception\InternalHttpException $e) {
        // We can get the response here to check the status code of the error or response body.
        $response = $e->getResponse();

        return $response;
    }
    return view('site.trips.register')->with('trip', $trip);
});


Route::get('/login', 'Auth\AuthController@login');
Route::post('/login', 'Auth\AuthController@authenticate');
Route::get('/register', 'Auth\Authcontroller@create');
Route::post('/register', 'Auth\AuthController@register');
Route::get('/logout', 'Auth\AuthController@logout');

Route::get('/', function () {
    return view('site.index');
});
