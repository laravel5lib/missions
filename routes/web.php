<?php

use Artesaos\SEOTools\Facades\SEOMeta;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
*/

$this->group(['middleware' => ['auth'], 'prefix' => 'dashboard' ], function () {
    $this->get('/', function () {
        SEOMeta::setTitle('Dashboard');

        return view('dashboard.index');
    });

    $this->get('settings', function () {
        SEOMeta::setTitle('Settings');

        return view('dashboard.settings');
    });

    $this->get('reports', function () {
        if (! auth()->user()->managing()->count()) {
            abort(403);
        }

        SEOMeta::setTitle('Reports');

        return view('dashboard.reports.index');
    });

    // Group Routes...
    $this->resource('groups', 'Dashboard\GroupsController', ['only' => ['index', 'show', 'edit']]);
    $this->get('groups/{groupId}/trips/{id}/{tab?}', 'Dashboard\GroupsController@trips');
    $this->get('groups/{groupId}/teams', 'Dashboard\GroupsController@teams');
    $this->get('groups/{groupId}/rooms', 'Dashboard\GroupsController@rooms');

    // Records Routes...
    $this->get('records/{tab?}', function ($tab = 'passports') {
        $title = title_case(str_replace("-"," ",$tab));
        SEOMeta::setTitle($title);

        return view('dashboard.'.$tab.'.index', compact('tab'));
    });
    $this->resource('records/passports', 'Dashboard\PassportsController', [
        'except' => ['index', 'destroy']
    ]);
    $this->resource('records/visas', 'Dashboard\VisasController', [
        'except' => ['index', 'destroy']
    ]);
    $this->resource('records/medical-releases', 'Dashboard\MedicalReleasesController', [
        'except' => ['index', 'destroy']
    ]);
    $this->resource('records/medical-credentials', 'Dashboard\MedicalCredentialsController', [
        'except' => ['index', 'destroy']
    ]);
    $this->resource('records/media-credentials', 'Dashboard\MediaCredentialsController', [
        'except' => ['index', 'destroy']
    ]);
    $this->resource('records/essays', 'Dashboard\EssaysController', [
        'except' => ['index', 'destroy']
    ]);
    $this->resource('records/influencers', 'Dashboard\InfluencersController', [
        'except' => ['index', 'destroy']
    ]);
    $this->resource('records/referrals', 'Dashboard\ReferralsController', [
        'except' => ['index', 'destroy']
    ]);

    // Reservation Routes...
    $this->get('reservations', 'Dashboard\ReservationsController@index');
    $this->get('reservations/{id}/{tab?}', 'Dashboard\ReservationsController@show');

    // Project Routes...
    $this->get('projects', 'Dashboard\ProjectsController@index');
    $this->get('projects/{id}/{tab?}', 'Dashboard\ProjectsController@show');
});

/*
|--------------------------------------------------------------------------
| Redirects
|--------------------------------------------------------------------------
*/

$this->get('/signup/{slug}', function ($slug) {
    return redirect('/'.$slug.'/signup');
});
$this->get('/campaigns/{slug}', function ($slug) {
    return redirect('/'.$slug);
});
$this->get('/search', function () {
    return redirect('/fundraisers');
});
$this->get('/go/{slug?}', function ($slug = null) {
    if (! $slug) {
        return redirect('/fundraisers');
    }
    return redirect('/'.$slug);
});
$this->get('/1n1d19', function() {
    return redirect('/travel-with-us/signup?trip_id=4f3705a8-fbae-45fe-927f-c3042a8d9127');
});

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Auth::routes();

$this->get('/donate/{recipient?}', 'DonationsController@show');
$this->get('/referrals/{id}', 'ReferralsController@show');
$this->get('/campaigns', function () {
    return redirect('/trips');
});
$this->get('/trips', 'CampaignsController@index');
$this->get('/trips/{id}', 'TripsController@show');
$this->get('/trips/{id}/register', 'TripsController@register')->middleware('auth');
$this->get('/fundraisers', 'FundraisersController@index');
$this->get('/support', function () {
    return redirect('/partnership');
});
$this->get('/groups', function () {
    return redirect('/teams');
});
$this->get('/teams', 'GroupsController@index');
$this->get('/{slug}/signup', 'GroupsController@signup')
     ->where('sponsor_slug', '^(?!api).*$');
$this->get('/{slug}/trips', 'CampaignsController@trips')
     ->where('sponsor_slug', '^(?!api).*$');
$this->get('/{sponsor_slug}/{fundraiser_slug}', 'FundraisersController@show')
     ->where('sponsor_slug', '^(?!api).*$')->name('fundraiser');
$this->get('/{slug}', 'PagesController@show')
     ->where('sponsor_slug', '^(?!api).*$')
     ->middleware(['lowercase']);
$this->get('/', function () {
    return view('site.index');
});
