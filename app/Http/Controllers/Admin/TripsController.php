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

    public function show($id, $tab = 'details')
    {
        if ($tab === 'edit') {
            return $this->edit($id);
        }

        $trip = $this->api->get('trips/'.$id, ['include' => 'campaign,costs.payments,requirements,notes,deadlines']);

        return view('admin.trips.show', compact('trip', 'tab'));
    }

    public function edit($tripId)
    {
        $trip = $this->api->get('trips/'.$tripId, ['include' => 'campaign']);
        return view('admin.trips.edit', compact('tripId', 'trip'));
    }

    public function create($campaignId)
    {
        // $campaign = Campaign::findOrFail($campaignId);
        $campaign = $this->api->get('campaigns/'.$campaignId);
        return view('admin.trips.create')->with('campaign', $campaign);
    }
}
