<?php

namespace App\Http\Controllers\admin;

use App\Models\v1\Campaign;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TripInterestsController extends Controller
{

    public function index()
    {
//        $interests = $this->api->get('interests');

        return view('admin.interests.index');
    }

    public function show($id)
    {
        $interest = $this->api->get('interests/'.$id, ['include' => '']);

        return view('admin.interests.show')->with('trip', $interest);
    }

    public function edit($tripId)
    {

        return view('admin.interests.edit')->with('tripId', $tripId);
    }

    public function create($campaignId)
    {
        $campaign = Campaign::whereId($campaignId)->orWhere('page_url', $campaignId)->first();
        // $this->api->get('campaigns/'.$campaignId);
        return view('admin.interests.create')->with('campaign', $campaign);
    }
}
