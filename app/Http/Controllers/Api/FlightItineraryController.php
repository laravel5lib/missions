<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\v1\FlightItinerary;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\FlightItineraryResource;

class FlightItineraryController extends Controller
{
    public function index($campaignId)
    {
        $query = FlightItinerary::forCampaign($campaignId);
        
        $itineraries = QueryBuilder::for($query)
            ->allowedFilters('record_locator')
            ->withCount('flights')
            ->withCount('reservations')
            ->paginate(25);

        return FlightItineraryResource::collection($itineraries);
    }
}
