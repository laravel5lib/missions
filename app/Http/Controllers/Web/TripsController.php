<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TripsController extends Controller
{
    public function show($id)
    {
        try {
            $trip = $this->api->get('trips/'. $id, ['include' => 'campaign,costs.payments,requirements,notes,deadlines']);
        } catch (Dingo\Api\Exception\InternalHttpException $e) {
            // We can get the response here to check the status code of the error or response body.
            $response = $e->getResponse();

            return $response;
        }

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
        return view('site.trips.register')->with('trip', $trip);
    }
}
