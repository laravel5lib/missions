<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VisaResource extends JsonResource
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
            'user_id'      => $this->user_id,
            'given_names'  => $this->given_names,
            'surname'      => $this->surname,
            'number'       => $this->number,
            'country_code' => $this->country_code,
            'country_name' => country($this->country_code),
            'upload_id'    => $this->upload_id,
            'issued_at'    => $this->issued_at->toDateString(),
            'expires_at'   => $this->expires_at->toDateString(),
            'created_at'   => $this->created_at->toIso8601String(),
            'updated_at'   => $this->updated_at->toIso8601String(),
            'expired'      => $this->expires_at->isPast(),
            'user'         => new UserResource($this->whenLoaded('user')),
            'upload'       => new MediaResource($this->whenLoaded('upload'))
        ];
    }
}
