<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

use Illuminate\Http\Request;

$dispatcher = app('Dingo\Api\Dispatcher');

Route::group(['middleware' => 'auth', 'prefix' => 'dashboard'], function () use($dispatcher)
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
        return view('dashboard.groups.show', compact('group', 'id'));
    });

    Route::get('groups/{groupId}/trips/{id}', function($groupId, $id) use ($dispatcher) {
        if( ! auth()->user()->managing()->count()) abort(403);
        $group = $dispatcher->get('groups/' . $groupId);
        $trip = $dispatcher->get('trips/' . $id);
        return view('dashboard.groups.trips.show', compact('group', 'groupId', 'trip', 'id'));
    });

    Route::get('records', function () {
        return view('dashboard.records.index');
    });

    // Passports
    Route::get('passports', function () {
        return view('dashboard.passports.index');
    });

    Route::get('passports/create', function () {
        return view('dashboard.passports.create');
    });

    Route::get('passports/{id}', function ($id) {
        return view('dashboard.passports.index', compact('id'));
    });

    Route::get('passports/{id}/edit', function ($id) {
        return view('dashboard.passports.edit', compact('id'));
    });

    // Visas
    Route::get('visas', function () {
        return view('dashboard.visas.index');
    });

    Route::get('visas/create', function () {
        return view('dashboard.visas.create');
    });

    Route::get('visas/{id}', function ($id) {
        return view('dashboard.visas.index', compact('id'));
    });

    Route::get('visas/{id}/edit', function ($id) {
        return view('dashboard.visas.edit', compact('id'));
    });


    Route::get('/reservations', function () use ($dispatcher) {

        return view('dashboard.reservations.index');
    });

    Route::get('/reservations/{id}', function ($id) use ($dispatcher) {
        try {
            $reservation = $dispatcher->get('reservations/' . $id, ['include' => 'trip.campaign']);
        } catch (Dingo\Api\Exception\InternalHttpException $e) {
            // We can get the response here to check the status code of the error or response body.
            $response = $e->getResponse();

            return $response;
        }
        $active = 'details';
        return view('dashboard.reservations.show', compact('reservation', 'active'));
    });

    Route::get('/reservations/{id}/requirements', function ($id, Request $request) use ($dispatcher) {
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

    Route::get('/reservations/{id}/funding', function ($id, Request $request) use ($dispatcher) {
        try {
            $reservation = $dispatcher->get('reservations/' . $id, ['include' => 'trip.campaign,fundraisers,costs.payments']);
        } catch (Dingo\Api\Exception\InternalHttpException $e) {
            // We can get the response here to check the status code of the error or response body.
            $response = $e->getResponse();

            return $response;
        }

        // Calculate total due
        $totalAmountDue = 0;
        foreach ($reservation->costs as $cost) {
            $totalAmountDue += $cost->amount;
            if($cost->type === 'incremental') {
                foreach ($cost->payments as $payment) {
                    if($payment->due_at->gt(now())){
                        $payment->due_next = true;
                        break;
                    }
                }
            }
        }
        // Calculate total raised
        $totalAmountRaised = 0;
        foreach($reservation->fundraisers as $fundraiser) {
            $totalAmountRaised = $fundraiser->raised() / 100;
        }

        $active = $request->segment(4);
        return view('dashboard.reservations.funding', compact('reservation', 'totalAmountDue', 'totalAmountRaised', 'active'));
    });

    Route::get('/reservations/{id}/funding/{fid}/donations', function ($id, $fid, Request $request) use ($dispatcher) {
        try {
            $reservation = $dispatcher->get('reservations/' . $id, ['include' => 'trip.campaign']);
            $fundraiser = $dispatcher->get('fundraisers/' . $fid);
    //        $donations = $fundraiser->donations();
    //        return response()->json($fundraiser);
        } catch (Dingo\Api\Exception\InternalHttpException $e) {
            // We can get the response here to check the status code of the error or response body.
            $response = $e->getResponse();

            return $response;
        }
        $active = $request->segment(4);
        return view('dashboard.reservations.donations', compact('reservation', 'fundraiser', 'active'));
    });

    Route::get('/reservations/{id}/deadlines', function ($id, Request $request) use ($dispatcher) {
        try {
            $reservation = $dispatcher->get('reservations/' . $id, ['include' => 'trip.campaign,deadlines,requirements,costs.payments']);

            // Pluck payments
            $payments = [];
            foreach ($reservation->costs as $cost) {
                foreach ($cost->payments as $payment) {
                    $payment->cost_name = $cost->name;
                    $payments[] = $payment;
                }
            }
            //return response()->json($payments);
            // add 'due_at' property to deadlines just as payments and requirements
            $deadlines = [];
            foreach ($reservation->deadlines as $key => $deadline) {
                $deadline->due_at = $deadline->date->toDateTimeString();
                $deadlines[] = $deadline;
            }

            $requirements = $reservation->requirements->toArray();

            $merged_deadlines = array_merge($deadlines, $requirements, $payments);
            // Sort merged deadlines by due_at property
            usort($merged_deadlines, function($a, $b) {
                $ad = carbon($a['due_at']);
                $bd = carbon($b['due_at']);

                if ($ad->eq($bd)) {
                    return 0;
                }

                return $ad->lt($bd) ? -1 : 1;
            });
            $all_deadlines = collect($merged_deadlines);

        } catch (Dingo\Api\Exception\InternalHttpException $e) {
            // We can get the response here to check the status code of the error or response body.
            $response = $e->getResponse();

            return $response;
        }
        $active = $request->segment(4);
        return view('dashboard.reservations.deadlines', compact('reservation', 'active', 'all_deadlines'));
    });
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
Route::get('/profiles/{slug}', 'UsersController@profile');
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


Route::get('/{slug}', function ($slug) {
    return $slug;
});

Route::get('/{sponsor_slug}/{fundraiser_slug}', 'FundraisersController@show')->where('sponsor_slug', '.+');

Route::get('/', function () {
    return view('site.index');
});
