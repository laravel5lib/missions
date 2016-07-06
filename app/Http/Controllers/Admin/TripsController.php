<?php

namespace App\Http\Controllers\admin;

use App\Models\v1\Campaign;
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

    public function show($campaignId, $id)
    {
        $trip = $this->api->get('trips/'.$id, ['include' => 'campaign,costs.payments,requirements,notes,deadlines']);

        return view('admin.trips.show')->with('trip', $trip);
    }

    public function edit($campaignId, $tripId=null)
    {

        return view('admin.trips.edit')->with('tripId', $tripId);
    }

    public function create($campaignId)
    {
        $campaign = Campaign::whereId($campaignId)->orWhere('page_url', $campaignId)->first();
        // $this->api->get('campaigns/'.$campaignId);
        return view('admin.trips.create')->with('campaign', $campaign);
    }
}
