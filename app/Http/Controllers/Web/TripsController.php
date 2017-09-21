<?php

namespace App\Http\Controllers\Web;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Artesaos\SEOTools\Traits\SEOTools;

class TripsController extends Controller
{
    use SEOTools;

    public function show($id)
    {
        try {
            $trip = $this->api->get('trips/'. $id, ['include' => 'campaign,costs.payments,requirements,notes,deadlines']);
        } catch (Dingo\Api\Exception\InternalHttpException $e) {
            // We can get the response here to check the status code of the error or response body.
            $response = $e->getResponse();

            return $response;
        }

        if (! $trip->public) {
            abort(403);
        }

        $this->seo()->setTitle($trip->group->name.' - '.ucwords($trip->type).' Trip');

        return view('site.trips.show')->with('trip', $trip);
    }

    public function register($id)
    {
        try {
            $trip = $this->api->get('trips/'. $id);
        } catch (Dingo\Api\Exception\InternalHttpException $e) {
            // We can get the response here to check the status code of the error or response body.
            $response = $e->getResponse();

            return $response;
        }

        $this->seo()->setTitle('Register for '.$trip->group->name.'\'s '.ucwords($trip->type).' Trip');

        return view('site.trips.register')->with('trip', $trip);
    }
}
