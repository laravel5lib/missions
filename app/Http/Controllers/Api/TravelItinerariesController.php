<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Models\v1\Itinerary;
use Illuminate\Http\Request;
use App\Services\TravelActivity;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\TravelItineraryRequest;
use App\Http\Transformers\v1\ItineraryTransformer;

class TravelItinerariesController extends Controller
{   
    private $itinerary;
    private $travelActivity;

    function __construct(Itinerary $itinerary, TravelActivity $travelActivity)
    {
        $this->itinerary = $itinerary;
        $this->travelActivity = $travelActivity;
    }

    public function store(TravelItineraryRequest $request)
    {
        $reservation = $this->api->get('reservations/' . $request->get('reservation_id'));

        $activities = collect([]);

        collect($request->json('items'))->each(function ($item) use ($reservation, $activities) {

            $transport = collect(isset($item['transport']) ? $item['transport'] : []);
            $activity = collect(isset($item['activity']) ? $item['activity'] : []);
            $hub = collect(isset($item['hub']) ? $item['hub'] : []);

            $data = $this->travelActivity->create([
                'transport' => [
                    'type' => $transport->get('type'),
                    'vessel_no' => $transport->get('vessel_no'),
                    'name' => $transport->get('name'),
                    'call_sign' => $transport->get('call_sign'),
                    'domestic' => $transport->get('domestic', true),
                    'capacity' => $transport->get('capacity', 9999),
                    'campaign_id' => $reservation->trip->campaign->id
                ],
                'passenger' => [
                    'reservation_id' => $reservation->id
                ],
                'activity' => [
                    'name' => $activity->get('name'),
                    'description' => $activity->get('description'),
                    'occurred_at' => $activity->get('occurred_at'),
                    'participant_id' => $reservation->id,
                    'participant_type' => 'reservations'
                ],
                'hub' => [
                    'name' => $hub->get('name'),
                    'call_sign' => $hub->get('iata'),
                    'city' => $hub->get('city'),
                    'country_code' => country_code($hub->get('country')),
                    'campaign_id' => $reservation->trip->campaign->id
                ]
            ]);

            $activities->push($data->id);
        });

        $itinerary = $this->itinerary->create([
            'name' => $reservation->name.'\'s Travel Itinerary',
            'curator_id' => $reservation->id,
            'curator_type' => 'reservations'
        ]);

        $itinerary->activities()->sync($activities->all(), false);

        return $this->response->item($itinerary, new ItineraryTransformer);
    }
}
