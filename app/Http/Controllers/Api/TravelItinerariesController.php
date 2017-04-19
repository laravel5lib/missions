<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\TravelItineraryRequest;

class TravelItinerariesController extends Controller
{   
    public function show($id)
    {
        //
    }

    public function store(TravelItineraryRequest $request)
    {
        $reservation = $this->api->get('reservations/' . $request->get('reservation_id'));

        $transportData = [
            'type' => $request->json('transport.type'),
            'vessel_no' => $request->json('transport.vessel_no'),
            'name' => $request->json('transport.name'),
            'call_sign' => $request->json('transport.call_sign'),
            'domestic' => $request->json('transport.domestic', true),
            'capacity' => $request->json('transport.capacity', 9999),
            'campaign_id' => $reservation->trip->campaign->id
        ];

        $activityData = [
            'name' => $request->json('activity.name'),
            'description' => $request->json('activity.description'),
            'occurred_at' => $request->json('activity.occurred_at'),
            'participant_id' => $request->json('reservation_id'),
            'participant_type' => 'reservations'
        ];

        try {
            $transport = $this->api->json($transportData)->post('transports');

            $activity = $this->api->json($activityData)->post('activities');

            $response = $this->api
                             ->json(['activities' => [$activity->id]])
                             ->post('transports/' . $transport->id . '/activities');

        } catch (\Exception $e) {
            isset($transport) ? $transport->delete() : null;
            isset($activity) ? $activity->delete() : null;

            throw $e;
        }

        return $this->response->created();
    }
}
