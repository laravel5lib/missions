<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TripTemplateResource extends JsonResource
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
            'id'              => $this->id,
            'name'            => $this->name,
            'campaign_id'     => $this->campaign_id,
            'spots'           => $this->spots,
            'companion_limit' => $this->companion_limit,
            'country_code'    => $this->country_code,
            'country_name'    => country($this->country_code),
            'type'            => $this->type,
            'difficulty'      => $this->difficulty,
            'started_at'      => $this->started_at->toDateString(),
            'ended_at'        => $this->ended_at->toDateString(),
            'todos'           => $this->todos ?? [],
            'prospects'       => $this->prospects ?? [],
            'team_roles'      => $this->team_roles ?? [],
            'description'     => $this->description,
            'closed_at'       => optional($this->closed_at)->toIso8601String(),
            'created_at'      => $this->created_at->toIso8601String(),
            'updated_at'      => $this->updated_at->toIso8601String(),
            'tags'            => TagResource::collection($this->whenLoaded('tags'))
        ];
    }
}
