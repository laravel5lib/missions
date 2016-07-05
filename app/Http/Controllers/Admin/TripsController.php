<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TripsController extends Controller
{

    public function index()
    {
        $trips = $this->api->get('trips');

        return view('admin.trips.index')->with('trips', $trips);
    }

    public function show($id)
    {
        $trip = $this->api->get('trips/'.$id);

        return view('admin.trips.show')->with('trip', $trip);
    }

    public function edit($id, $tripId=null)
    {

        return view('admin.trips.edit')->with('campaignId', $id);
    }

    public function create($campaignId)
    {
        $campaign = $this->api->get('campaigns/'.$campaignId);

        return view('admin.trips.create')->with('campaign', $campaign);
    }
}
