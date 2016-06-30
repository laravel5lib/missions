<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TripsController extends Controller
{

    public function show($id)
    {
        //$campaign = $this->api->get('campaigns/'.$id);

        return view('admin.campaigns.trips.show')->with('campaignId', $id);
    }

    public function edit($id)
    {
        //$campaign = $this->api->get('campaigns/'.$id);

        return view('admin.campaigns.trips.edit')->with('campaignId', $id);
    }

    public function create()
    {
        return view('admin.campaigns.trips.create');
    }
}
