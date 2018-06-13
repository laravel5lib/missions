<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Jobs\NotifyOfPublishedFlights;

class FlightItineraryPublicationController extends Controller
{
    public function update(Request $request)
    {   
        $itineraries = $request->input('itineraries');
        $status = $request->input('published');

        foreach($itineraries as $itinerary)
        {
            DB::table('flight_itineraries')
                ->where('uuid', $itinerary)
                ->update(['published' => $status]);
        }

        if ($status) {
            NotifyOfPublishedFlights::dispatch($itineraries);
        }

        return response()->json(['message' => 'Flight itineraries updated.'], 200);
    }
}
