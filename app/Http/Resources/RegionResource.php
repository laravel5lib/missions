<?php

namespace App\Http\Resources;

use App\Http\Resources\CampaignResource;
use Illuminate\Http\Resources\Json\JsonResource;

class RegionResource extends JsonResource
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
            'name' => $this->name,
            'squads_count' => $this->squads_count,
            'missionaires_count' => $this->members_count,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->created_at->toIso8601String(),
            'deleted_at' => optional($this->deleted_at)->toIso8601String(),
            'campaign' => new CampaignResource($this->whenLoaded('campaign'))
        ];
    }
}
