<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

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

        return response()->json(['message' => 'Flight itineraries updated.'], 200);
    }
}
