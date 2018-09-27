<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'first_name'   => $this->first_name,
            'last_name'    => $this->last_name,
            'email'        => $this->email,
            'password'     => $this->password,
            'alt_email'    => $this->alt_email,
            'gender'       => $this->gender,
            'status'       => $this->status,
            'birthday'     => optional($this->birthday)->toDateString(),
            'phone_one'    => $this->phone_one,
            'phone_two'    => $this->phone_two,
            'address'       => $this->address,
            'city'         => $this->city,
            'state'        => $this->state,
            'zip'          => $this->zip,
            'country_code' => $this->country_code,
            'country_name' => country($this->country_code),
            'shirt_size'   => strtoupper($this->shirt_size),
            'timezone'     => $this->timezone,
            'bio'          => $this->bio,
            'url'          => $this->url,
            'avatar'       => $this->avatar ? image($this->avatar->source) : url('/images/placeholders/user-placeholder.png'),
            'avatar_upload_id' => $this->avatar_upload_id,
            'banner'       => $this->banner ? image($this->banner->source) : null,
            'public'       => (bool) $this->public,
            'created_at'   => $this->created_at->toIso8601String(),
            'updated_at'   => $this->updated_at->toIso8601String()
        ];
    }
}
