<?php

namespace App\Http\Resources;

use App\Http\Resources\FlightResource;
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
            'type' => $this->flightItinerary->type,
            'record_locator' => $this->flightItinerary->record_locator,
            'flight' => new FlightResource($this->flightItinerary->flights->first())
        ];
    }
}
