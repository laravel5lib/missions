<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\v1\FlightSegment;
use App\Http\Controllers\Controller;
use App\Http\Requests\FlightSegmentRequest;
use App\Http\Resources\FlightSegmentResource;

class FlightSegmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($campaignId)
    {
        $segments = FlightSegment::where('campaign_id', $campaignId)
            ->orderBy('name')
            ->get();

        return FlightSegmentResource::collection($segments);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($campaignId, FlightSegmentRequest $request)
    {
        FlightSegment::create(['name' => $request->name, 'campaign_id' => $campaignId]);

        return response()->json(['message' => 'New segment created.'], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param string $campaignId
     * @param FlightSegmentRequest $request
     * @param string $flightSegment
     * @return \Illuminate\Http\Response
     */
    public function update($campaignId, FlightSegmentRequest $request, $flightSegment)
    {
        $segment = FlightSegment::whereUuid($flightSegment)
            ->where('campaign_id', $campaignId)
            ->firstOrFail();
        
        $segment->update(['name' => $request->name]);

        return new FlightSegmentResource($segment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FlightSegment  $flightSegment
     * @return \Illuminate\Http\Response
     */
    public function destroy($campaignId, $flightSegment)
    {
        $segment = FlightSegment::whereUuid($flightSegment)
            ->where('campaign_id', $campaignId)
            ->firstOrFail();
        
        $segment->delete();

        return response()->json(['message' => 'Flight segment deleted.'], 204);
    }
}
