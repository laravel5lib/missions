<?php

namespace App\Http\Resources;

use App\Http\Resources\RegionResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SquadResource extends JsonResource
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
            'callsign' => $this->callsign,
            'members_count' => $this->members_count,
            'status' => $this->published ? 'published' : 'draft',
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            'region' => new RegionResource($this->whenLoaded('region'))
        ];
    }
}
