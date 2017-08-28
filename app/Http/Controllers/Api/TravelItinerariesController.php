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

    /**
     * Create a new travel itinerary
     *
     * @param  TravelItineraryRequest $request
     * @return Response
     */
    public function store(TravelItineraryRequest $request)
    {
        $reservation = $this->api->get('reservations/' . $request->get('reservation_id'));

        $activities = collect([]);

        collect($request->json('items'))->each(function ($item) use ($reservation, $activities) {

            $transport = isset($item['transport']) ? collect($item['transport']) : null;
            $activity = isset($item['activity']) ? collect($item['activity']) : collect([]);
            $hub = isset($item['hub']) ? collect($item['hub']) : null;


            $data = [
                'activity' => [
                    'name'             => $activity->get('name'),
                    'activity_type_id' => $activity->get('activity_type_id'),
                    'description'      => $activity->get('description'),
                    'occurred_at'      => $activity->get('occurred_at') ?: $reservation->trip->ended_at->toDateTimeString(),
                    'participant_id'   => $reservation->id,
                    'participant_type' => 'reservations'
                ]
            ];

            if ($transport) {
                $data['transport'] = [
                    'type'        => $transport->get('type'),
                    'vessel_no'   => $transport->get('vessel_no') ?: 'unassigned',
                    'name'        => $transport->get('name'),
                    'call_sign'   => $transport->get('call_sign') ?: null,
                    'domestic'    => $transport->get('domestic', true),
                    'capacity'    => $transport->get('capacity', 9999),
                    'campaign_id' => $reservation->trip->campaign->id
                ];
                $data['passenger'] = [
                    'reservation_id' => $reservation->id
                ];
            }

            if ($hub) {
                $data['hub'] = [
                    'name'         => $hub->get('name'),
                    'call_sign'    => $hub->get('call_sign'),
                    'city'         => $hub->get('city'),
                    'country_code' => $hub->get('country_code'),
                    'campaign_id'  => $reservation->trip->campaign->id
                ];
            }


            $travelActivity = $this->travelActivity->create($data);

            $activities->push($travelActivity->id);
        });

        $itinerary = $this->itinerary->create([
            'name'         => $reservation->name.'\'s Travel Itinerary',
            'curator_id'   => $reservation->id,
            'curator_type' => 'reservations'
        ]);

        $itinerary->activities()->sync($activities->all(), false);

        return $this->response->item($itinerary, new ItineraryTransformer);
    }

    /**
     * Update an existing travel itinerary
     *
     * @param  TravelItineraryRequest $request
     * @param  String                 $id
     * @return Response
     */
    public function update(TravelItineraryRequest $request, $id)
    {
        $itinerary = $this->itinerary->findOrFail($id);

        collect($request->json('items'))->each(function ($item) {

            $transport = isset($item['transport']) ? collect($item['transport']) : null;
            $activity = isset($item['activity']) ? collect($item['activity']) : collect([]);
            $hub = isset($item['hub']) ? collect($item['hub']) : null;

            $data = [
                'activity' => [
                    'id'          => $activity->get('id'),
                    'name'        => $activity->get('name'),
                    'description' => $activity->get('description'),
                    'occurred_at' => $activity->get('occurred_at')
                ]
            ];

            if ($transport) {
                $data['transport'] = [
                    'id'          => $transport->get('id'),
                    'type'        => $transport->get('type'),
                    'vessel_no'   => $transport->get('vessel_no'),
                    'name'        => $transport->get('name'),
                    'call_sign'   => $transport->get('call_sign'),
                    'domestic'    => $transport->get('domestic'),
                    'capacity'    => $transport->get('capacity')
                ];
            }

            if ($hub) {
                $data['hub'] = [
                    'id'           => $hub->get('id'),
                    'name'         => $hub->get('name'),
                    'call_sign'    => $hub->get('call_sign'),
                    'city'         => $hub->get('city'),
                    'country_code' => $hub->get('country_code')
                ];
            }

            $this->travelActivity->update($data);
        });

        return $this->response->item($itinerary, new ItineraryTransformer);
    }

    /**
     * Delete a travel itinerary
     *
     * @param  String $id
     * @return Response
     */
    public function destroy($id)
    {
        $itinerary = $this->itinerary->findOrFail($id);

        $itinerary->activities->each(function ($activity) use ($itinerary) {
            $activity->transports->each(function ($transport) use ($itinerary) {
                $transport->passengers()->whereReservationId($itinerary->curator_id)->delete();
            });
        });

        $itinerary->activities()->delete();
        $itinerary->delete();

        return $this->response->noContent();
    }
}
