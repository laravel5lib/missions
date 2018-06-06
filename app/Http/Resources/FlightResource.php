<?php

namespace App\Http\Resources;

use App\Http\Resources\FlightSegmentResource;
use Illuminate\Http\Resources\Json\JsonResource;

class FlightResource extends JsonResource
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
            'id' => $this->uuid,
            'flight_no' => $this->flight_no,
            'date' => $this->date->toDateString(),
            'time' => $this->time,
            'iata_code' => $this->iata_code,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            'segment' => new FlightSegmentResource($this->whenLoaded('flightSegment')),
        ];
    }
}
