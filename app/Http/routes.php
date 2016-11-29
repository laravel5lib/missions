<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

use Illuminate\Http\Request;

$dispatcher = app('Dingo\Api\Dispatcher');

Route::group(['middleware' => ['auth', 'can:access-dashboard'], 'prefix' => 'dashboard'], function () use($dispatcher)
{
    Route::get('/', function () {
        return view('dashboard.index');
    });

    Route::get('settings', function() {
        return view('dashboard.settings');
    });

    Route::get('groups', function() {
        if( ! auth()->user()->managing()->count()) abort(403);
        return view('dashboard.groups.index');
    });

    Route::get('groups/{id}', function($id) use ($dispatcher) {
        if( ! auth()->user()->managing()->count()) abort(403);
        $group = $dispatcher->get('groups/' . $id);
        return view('dashboard.groups.show', compact('group', 'id'));
    });

    Route::get('groups/{id}/edit', function($id) use ($dispatcher) {
        if( ! auth()->user()->managing()->count()) abort(403);
        $group = $dispatcher->get('groups/' . $id);
        return view('dashboard.groups.edit', compact('group', 'id'));
    });

    Route::get('groups/{groupId}/trips/{id}', function($groupId, $id) use ($dispatcher) {
        if( ! auth()->user()->managing()->count()) abort(403);
        $group = $dispatcher->get('groups/' . $groupId);
        $trip = $dispatcher->get('trips/' . $id);
        return view('dashboard.groups.trips.show', compact('group', 'groupId', 'trip', 'id'));
    });

    Route::get('records/{tab?}', function($tab = 'passports') {
        return view('dashboard.'.$tab.'.index', compact('tab'));
    });

    Route::get('records/passports/create', function () {
        return view('dashboard.passports.create');
    });

    Route::get('records/passports/{id}', function ($id) {
        return view('dashboard.passports.index', compact('id'));
    });

    Route::get('records/passports/{id}/edit', function ($id) {
        return view('dashboard.passports.edit', compact('id'));
    });

    Route::get('records/visas/create', function () {
        return view('dashboard.visas.create');
    });

    Route::get('records/visas/{id}', function ($id) {
        return view('dashboard.visas.index', compact('id'));
    });

    Route::get('records/visas/{id}/edit', function ($id) {
        return view('dashboard.visas.edit', compact('id'));
    });

    Route::get('records/medical-releases/create', function () {
        return view('dashboard.medical-releases.create');
    });

    Route::get('records/medical-releases/{id}', function ($id) {
        return view('dashboard.medical-releases.index', compact('id'));
    });

    Route::get('records/medical-releases/{id}/edit', function ($id) {
        return view('dashboard.medical-releases.edit', compact('id'));
    });

    Route::get('records/medical-releases/create', function () {
        return view('dashboard.medical-releases.create');
    });

    Route::get('records/essays/create', function () {
        return view('dashboard.essays.create');
    });

    Route::get('records/essays/{id}', function ($id) {
        return view('dashboard.essays.index', compact('id'));
    });

    Route::get('records/essays/{id}/edit', function ($id) {
        return view('dashboard.essays.edit', compact('id'));
    });

    Route::get('reservations', 'ReservationsController@index');
    Route::get('reservations/{id}/{tab?}', 'ReservationsController@show');
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

Route::get('/fundraisers', 'FundraisersController@index');
Route::get('/groups', function() {
    return view('site.groups.index');
});
Route::get('/groups/{slug}', 'GroupsController@profile');
Route::get('groups/{slug}/signup', 'GroupsController@signup');
Route::get('/profiles/{slug}', function ($slug) {
    return redirect('@'.$slug);
});
Route::get('/users/{slug}', function ($slug) {
   return redirect('@'.$slug);
});
Route::get('/@{slug}', 'UsersController@profile');
Route::get('@{slug}/donate', function ($slug) {
    $type = 'users';
    return view('site.donate', compact('type', 'slug'));
});

Route::get('{type}/{slug?}/donate', function ($type, $slug) {
    return view('site.donate', compact('type', 'slug'));
})->where('type', 'profiles|groups|reservations|trips');

Route::get('/donate', function () {
    return view('site.donate');
});
Route::get('/speakers', function () {
    return view('site.speakers');
});
Route::get('/water', function () {
    return view('site.water');
});
Route::get('/orphans', function () {
    return view('site.orphans');
});
Route::get('/college', function () {
    return view('site.college');
});
Route::get('/college-financial', function () {
    return view('site.college-financial');
});
Route::get('/support', function () {
    return view('site.support');
});
Route::get('/medical', function () {
    return view('site.medical');
});
Route::get('/about-mm', function () {
    return view('site.about-mm');
});
Route::get('/contact', function () {
    return view('site.contact');
});
Route::get('/1n1d13', function () {
    return view('site.1n1d13');
});
Route::get('/1n1d15', function () {
    return view('site.1n1d15');
});
Route::get('/sponsor-a-project', function () {
    return view('site.sponsor-a-project');
});

Route::get('/{slug}', function ($slug) {
    return $slug;
});

Route::get('/{sponsor_slug}/{fundraiser_slug}', 'FundraisersController@show')->where('sponsor_slug', '.+');

Route::get('/', function () {
    return view('site.index');
});
