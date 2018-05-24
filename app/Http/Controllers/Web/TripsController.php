<?php

namespace App\Http\Controllers\Web;

use App\Trip;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Traits\SEOTools;

class TripsController extends Controller
{
    use SEOTools;

    public function show($id)
    {
        $trip = Trip::getById($id);

        if (! $trip->published_at) {
            abort(404);
        }

        $this->seo()->setTitle($trip->group->name.' - '.ucwords($trip->type).' Trip');

        return view('site.trips.show', compact('trip'));
    }

    public function register($id)
    {
        $trip = Trip::getById($id);

        $this->seo()->setTitle('Register for '.$trip->group->name.'\'s '.ucwords($trip->type).' Trip');

        if (! $trip->public) {
            abort(403);
        }

        return view('site.trips.register', compact('trip'));
    }
}
