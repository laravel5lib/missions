<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

/**
 * Dashboard Routes
 */
$this->group(['middleware' => ['auth', 'can:access-dashboard'], 'prefix' => 'dashboard' ], function ()
{
    $this->get('/', function () {
        return view('dashboard.index');
    });

    $this->get('settings', function() {
        return view('dashboard.settings');
    });

    // Group Routes...
    $this->resource('groups', 'Dashboard\GroupsController', ['only' => ['index', 'show', 'edit']]);
    $this->get('groups/{groupId}/trips/{id}/{tab?}', 'Dashboard\GroupsController@trips');

    // Records Routes...
    $this->get('records/{tab?}', function($tab = 'passports') {
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
    $this->resource('records/essays', 'Dashboard\EssaysController', [
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

/**
 * Web Routes
 */
// Authentication and Registration Routes...
$this->get('/login', 'Auth\AuthController@login');
$this->post('/login', 'Auth\AuthController@authenticate');
$this->get('/register', 'Auth\AuthController@create');
$this->post('/register', 'Auth\AuthController@register');
$this->get('/logout', 'Auth\AuthController@logout');

// Password Reset Routes...
$this->get('/password/email', 'Auth\PasswordController@showLinkRequestForm');
$this->get('/password/reset/{token?}', 'Auth\PasswordController@showResetForm');
$this->post('/password/email', 'Auth\PasswordController@sendResetLinkEmail');
$this->post('/password/reset', 'Auth\PasswordController@reset');

// Donation Route...
$this->get('/donate/{recipient?}', 'DonationsController@show');

// Referral Form Route..
$this->get('/referrals/{id}', 'ReferralsController@show');

// Trip Routes...
$this->get('/trips/{id}', 'TripsController@show');
$this->get('/trips/{id}/register', 'TripsController@register');

// Fundraisers, Groups, Campaigns, and Static Page Routes ...
$this->get('/fundraisers', 'FundraisersController@index');
$this->get('/groups', 'GroupsController@index');
$this->get('/campaigns', 'CampaignsController@index');
$this->get('/{slug}/signup', 'GroupsController@signup');
$this->get('/{slug}/trips', 'CampaignsController@trips');
$this->get('/{sponsor_slug}/{fundraiser_slug}', 'FundraisersController@show')->where('sponsor_slug', '.+');
$this->get('/{slug}', 'PagesController@show');

// Home Route ...
$this->get('/', function () {
    return view('site.index');
});
