<?php

namespace App\Http\Controllers\Admin;

use App\Models\v1\Flight;
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

        $totals = [
            'booked' => $campaign->reservations()->whereNotNull('flight_itinerary_id')->count(),
            'not_booked' => $campaign->reservations()->whereNull('flight_itinerary_id')->count(),
            'no_flight' => 0
        ];

        return view('admin.flights.index', compact('campaign', 'segments', 'totals'));
    }

    public function show($flight)
    {
        $flight = Flight::whereUuid($flight)->firstOrFail();

        $campaign = Campaign::findOrFail($flight->flightSegment->campaign_id);

        return view('admin.flights.show', compact('flight', 'campaign'));
    }

    public function edit($flight, Request $request)
    {
        $flight = Flight::whereUuid($flight)->firstOrFail();

        $campaign = Campaign::findOrFail($flight->flightSegment->campaign_id);

        return view('admin.flights.edit', compact('flight', 'campaign'));
    }
}
