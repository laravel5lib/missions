<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CampaignResource extends JsonResource
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
            'id'           => $this->id,
            'name'         => $this->name,
            'country'      => [
                'name' => country($this->country_code),
                'code' => $this->country_code
            ],
            'description'  => $this->short_desc,
            'page_url'     => optional($this->slug)->url,
            'page_src'     => $this->page_src,
            'avatar'       => $this->avatar ? image($this->avatar->source) : url('/images/placeholders/this-placeholder.png'),
            'avatar_upload_id' => $this->avatar_upload_id,
            'started_at'   => $this->started_at->toDateString(),
            'ended_at'     => $this->ended_at->toDateString(),
            'status'       => $this->status,
            'groups_count' => $this->groups->count(),
            'reservations_locked' => $this->reservations_locked,
            'published_at' => optional($this->published_at)->toIso8601String(),
            'created_at'   => $this->created_at->toIso8601String(),
            'updated_at'   => $this->updated_at->toIso8601String(),
        ];
    }
}
