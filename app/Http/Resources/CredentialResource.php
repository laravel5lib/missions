<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CredentialResource extends JsonResource
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
            'id'             => $this->id,
            'type'           => $this->type,
            'user_id'        => $this->user_id,
            'applicant_name' => $this->applicant_name,
            'content'        => $this->content,
            'expired_at'     => optional($this->expired_at)->toIso8601String(),
            'created_at'     => $this->created_at->toIso8601String(),
            'updated_at'     => $this->updated_at->toIso8601String(),
            'user'           => new UserResource($this->whenLoaded('user')),
            'uploads'        => MediaResource::collection($this->whenLoaded('uploads'))
        ];
    }
}
