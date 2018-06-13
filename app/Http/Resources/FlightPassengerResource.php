<?php

namespace App\Http\Resources;

use App\Http\Resources\FlightResource;
use App\Http\Resources\FlightItineraryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class FlightPassengerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'surname' => $this->surname,
            'given_names' => $this->given_names,
            'group' => $this->trip->group->name,
            'trip' => $this->trip->type,
            'itinerary' => new FlightItineraryResource($this->whenLoaded('flightItinerary')),
            'flight' => new FlightResource($this->flightItinerary->flights->first()),
            'passport' => $this->whenLoaded('passport', function () {
                return optional($this->passport()->first())->document;
            })
        ];
    }
}
