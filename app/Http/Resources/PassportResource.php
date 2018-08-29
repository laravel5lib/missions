<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PassportResource extends JsonResource
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
            'user_id' => $this->user_id,
            'given_names' => $this->given_names,
            'surname' => $this->surname,
            'number' => $this->number,
            'birth_country' => $this->birth_country,
            'birth_country_name' => country($this->birth_country),
            'citizenship' => $this->citizenship,
            'citizenship_name' => country($this->citizenship),
            'upload_id' => $this->upload_id,
            'expires_at' => $this->expires_at->format('Y-m-d'),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            'expired' => $this->expires_at->isPast() ? true : false,
            'user' => new UserResource($this->whenLoaded('user')),
            'upload' => new MediaResource($this->whenLoaded('upload'))
        ];
    }
}
