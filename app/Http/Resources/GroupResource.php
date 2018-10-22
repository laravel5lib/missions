<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
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
            'id'            => $this->id,
            'name'          => $this->name,
            'group_id'      => $this->whenPivotLoaded('campaign_group', function () {
                                    return $this->pivot->uuid;
                                }),
            'campaign_id'   => $this->whenPivotLoaded('campaign_group', function () {
                                    return $this->pivot->campaign_id;
                                }),
            'status'        => $this->whenPivotLoaded('campaign_group', function () {
                                    return groupStatus($this->pivot->status_id);
                                }),
            'meta'         => $this->whenPivotLoaded('campaign_group', function () {
                                    return $this->pivot->meta;
                                }),
            'trips'        => $this->whenPivotLoaded('campaign_group', function () {
                                    return $this->pivot->tripsCount();
                                }),
            'reservations'  => $this->whenPivotLoaded('campaign_group', function () {
                                    return $this->pivot->reservationsCount();
                                }),
            'commitment'  => $this->whenPivotLoaded('campaign_group', function () {
                                    return $this->pivot->commitment;
                                }),
            'type'         => $this->type,
            'timezone'     => $this->timezone,
            'description'  => $this->description,
            'url'          => $this->slug ? $this->slug->url : null,
            'public'       => (bool) $this->public,
            'address_one'  => $this->address_one,
            'address_two'  => $this->address_two,
            'city'         => $this->city,
            'state'        => $this->state,
            'zip'          => $this->zip,
            'country_code' => $this->country_code,
            'country_name' => country($this->country_code),
            'phone_one'    => $this->phone_one,
            'phone_two'    => $this->phone_two,
            'email'        => $this->email,
            'avatar'       => $this->avatar ? image($this->avatar->source) : url('/images/placeholders/logo-placeholder.png'),
            'banner'       => $this->banner ? image($this->banner->source) : null
        ];
    }
}
