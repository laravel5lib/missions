<?php

namespace App\Http\Controllers\Admin;

use App\Models\v1\Campaign;
use Illuminate\Http\Request;
use App\Models\v1\FlightSegment;
use App\Http\Controllers\Controller;

class FlightController extends Controller
{
    public function index($campaignId)
    {
        $campaign = Campaign::findOrFail($campaignId);

        $segments = FlightSegment::where('campaign_id', $campaign->id)->get();

        return view('admin.flights.index', compact('campaign', 'segments'));
    }
}
