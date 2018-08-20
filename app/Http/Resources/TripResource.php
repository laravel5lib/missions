<?php

namespace App\Http\Resources;

use App\Http\Resources\GroupResource;
use App\Http\Resources\PriceResource;
use App\Http\Resources\CampaignResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TripResource extends JsonResource
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
            'group_id'        => $this->group_id,
            'campaign_id'     => $this->campaign_id,
            'rep_id'          => $this->rep_id,
            'rep'             => optional($this->rep)->name,
            'spots'           => $this->spots,
            'status'          => $this->status,
            'starting_cost'   => $this->starting_cost,
            'companion_limit' => $this->companion_limit,
            'reservations'    => $this->reservations_count,
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
            'public'          => $this->public,
            'published_at'    => optional($this->published_at)->toIso8601String(),
            'closed_at'       => optional($this->closed_at)->toIso8601String(),
            'created_at'      => $this->created_at->toIso8601String(),
            'updated_at'      => $this->updated_at->toIso8601String(),
            'current_rate'    => $this->whenLoaded('priceables', function () {
                return new PriceResource($this->getCurrentRate());
            }),
            'prices'          => PriceResource::collection($this->whenLoaded('priceables')),
            'group'           => new GroupResource($this->whenLoaded('group')),
            'campaign'        => new CampaignResource($this->whenLoaded('campaign')),
            'requirements'    => RequirementResource::collection($this->whenLoaded('requireables')),
            'tags'            => TagResource::collection($this->whenLoaded('tags'))
        ];
    }
}
