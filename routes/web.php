<?php

use App\Models\v1\Fundraiser;
use Illuminate\Support\Facades\Route;
use Artesaos\SEOTools\Facades\SEOMeta;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
*/
Route::angelHouseRoutes();

$this->group(['middleware' => ['auth'], 'prefix' => 'dashboard' ], function () {
    $this->get('/', function () {
        SEOMeta::setTitle('Dashboard');

        return view('dashboard.index');
    });

    $this->get('settings', function () {
        SEOMeta::setTitle('Settings');

        $user = auth()->user();
        $user->load(['avatar', 'banner']);

        return view('dashboard.settings', compact('user'));
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
    $this->get('groups/{groupId}/campaigns/{id}', 'Dashboard\GroupsController@trips');
    $this->get('groups/{groupId}/campaigns/{id}/reservations', 'Dashboard\GroupsController@reservations');
    $this->get('groups/{groupId}/campaigns/{id}/flights', 'Dashboard\GroupsController@flights');
    $this->get('groups/{groupId}/campaigns/{id}/squads', 'Dashboard\GroupsController@squads');
    $this->get('groups/{groupId}/campaigns/{id}/interests', 'Dashboard\GroupsController@interests');
    $this->get('groups/{groupId}/trips/{id}/{tab?}', 'Dashboard\GroupsController@trip');
    $this->get('groups/{groupId}/teams', 'Dashboard\GroupsController@teams');
    $this->get('groups/{groupId}/rooms', 'Dashboard\GroupsController@rooms');

    // Records Routes...
    Route::get('records/{tab?}', 'Dashboard\DocumentController@index');
    Route::get('records/{tab}/create', 'Dashboard\DocumentController@create');
    Route::get('records/{tab}/{id}', 'Dashboard\DocumentController@show');
    Route::get('records/{tab}/{id}/edit', 'Dashboard\DocumentController@edit');

    // Reservation Routes...
    $this->get('reservations', 'Dashboard\ReservationsController@index');
    $this->get('reservations/{id}/requirements', 'Dashboard\ReservationRequirementController@index');
    $this->get('reservations/{id}/requirements/{requirement}', 'Dashboard\ReservationRequirementController@show');
    $this->get('reservations/{id}/{tab?}', 'Dashboard\ReservationsController@show')
         ->middleware(['hasUpfrontRequirement']);

    $this->get('/funds/{fund}/fundraisers/create', 'FundraisersController@create');
    $this->get('/fundraisers/{fundraiser}/edit', 'FundraisersController@edit');
    $this->get('/fundraisers/{fundraiser}/updates', function(Fundraiser $fundraiser) {
        return view('site.fundraisers.updates', compact('fundraiser'));
    });
    $this->get('/fundraisers/{fundraiser}/donations', function(Fundraiser $fundraiser) {
        return view('site.fundraisers.donations', compact('fundraiser'));
    });
    $this->get('/fundraisers/{fundraiser}/sharing', function(Fundraiser $fundraiser) {
        return view('site.fundraisers.sharing', compact('fundraiser'));
    });
});

/*
|--------------------------------------------------------------------------
| Redirects
|--------------------------------------------------------------------------
*/
$this->get('/victoriaorozco/nicaragua-2018-50', function() {
    return redirect ('/send-victoria-to-nicaragua');
});
$this->get('/jessicawright3390/dominican-republic-2018-10', function() {
    return redirect('/jessica-dominican-republic-2018-10');
});
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
$this->get('/1n1d19-interest', function () {
    return redirect('/travel-with-us/signup?trip_id=07b41b3b-7150-44fc-a69f-2843f7d1a513');
});

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Auth::routes();
Route::middleware('auth')->get('/verify/email', 'Auth\VerifyEmailController@verify')->name('verify.email');
Route::post('/verify/email/resend', 'Auth\VerifyEmailController@sendEmail')->name('verify.email.resend');
Route::get('/verify/continue/{userId}', 'Auth\VerifyEmailController@continue')->name('verify.email.continue');
Route::get('/verify/email/{token}', 'Auth\VerifyEmailController@confirm')->name('verify.email.token');

Route::middleware('auth')->get('files/{path}', '\App\Http\Controllers\Api\UploadsController@display_file')->where('path', '.+');

$this->get('/donate/{recipient?}', 'DonationsController@show');
$this->get('/referrals/{id}', 'ReferralsController@show');
$this->get('/campaigns', function () {
    return redirect('/trips');
});
$this->get('/fundraisers', 'FundraisersController@index');
$this->get('/trips', 'CampaignsController@index');
$this->get('/trips/{id}', 'TripsController@show');
$this->get('/trips/{id}/register', 'TripsController@register')->middleware('auth', 'verify');
$this->get('/support', function () {
    return redirect('/partnership');
});
$this->get('/groups', function () {
    return redirect('/teams');
});
$this->get('/teams', 'GroupsController@index');
$this->get('/teams/request', 'GroupsController@request');
$this->get('/fundraisers/{id}', 'FundraisersController@show');
$this->get('/{slug}/signup', 'GroupsController@signup')
     ->where('sponsor_slug', '^(?!api).*$');
$this->get('/{slug}/teams', 'CampaignsController@teams')
     ->where('sponsor_slug', '^(?!api).*$');
$this->get('/{slug}/teams/{teamId}/trips', 'CampaignsController@trips')
     ->where('sponsor_slug', '^(?!api).*$');

$this->get('/dashboard/fundraisers/{fundraiser}', function($fundraiser) {
    return redirect("/dashboard/fundraisers/$fundraiser->uuid/edit");
});

$this->get('/admin/fundraisers/{fundraiser}', function($fundraiser) {
    return redirect("/admin/fundraisers/$fundraiser->uuid/edit");
});

$this->get('/{sponsor_slug}/{fundraiser_slug}', function($sponsor_slug, $fundraiser_slug) {
    return redirect('/'.$fundraiser_slug);
})->where('sponsor_slug', '^(?!api).*$')->name('fundraiser');

$this->get('/', function () {
    return view('site.index');
});
$this->get('/{slug}', 'PagesController@show')
     ->where('slug', '^(?!api).*$')
     ->middleware(['lowercase']);
