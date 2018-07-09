<?php

namespace App\Http\Resources;

use App\Http\Resources\TripResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TripInterestResource extends JsonResource
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
            'id'                        => $this->id,
            'trip_id'                   => $this->trip_id,
            'trip'                      => new TripResource($this->whenLoaded('trip')),
            'status'                    => $this->status,
            'name'                      => $this->name,
            'email'                     => $this->email,
            'phone'                     => $this->phone,
            'communication_preferences' => $this->communication_preferences,
            'incomplete_tasks_count'    => $this->incomplete_tasks_count,
            'complete_tasks_count'      => $this->complete_tasks_count,  
            'created_at'                => $this->created_at->toIso8601String(),
            'updated_at'                => $this->updated_at->toIso8601String(),
        ];
    }
}
