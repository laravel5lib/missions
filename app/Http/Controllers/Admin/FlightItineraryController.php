<?php

namespace App\Http\Controllers\Admin;

use App\Models\v1\Campaign;
use Illuminate\Http\Request;
use App\Models\v1\FlightItinerary;
use App\Http\Controllers\Controller;

class FlightItineraryController extends Controller
{
    public function show($campaign, $itinerary)
    {
        $campaign = Campaign::findOrFail($campaign);
        $itinerary = FlightItinerary::whereUuid($itinerary)->firstOrFail();
        
        return view('admin.itineraries.show', compact('itinerary', 'campaign'));
    }

    public function edit($campaign, $itinerary)
    {
        $campaign = Campaign::findOrFail($campaign);
        $itinerary = FlightItinerary::whereUuid($itinerary)->firstOrFail();
        
        return view('admin.itineraries.edit', compact('itinerary', 'campaign'));
    }
}
