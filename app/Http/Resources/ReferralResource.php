<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReferralResource extends JsonResource
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
            'applicant_name' => $this->applicant_name,
            'type' => $this->type,
            'attention_to' => $this->attention_to,
            'recipient_email' => $this->recipient_email,
            'referrer' => $this->referrer,
            'response' => $this->response,
            'status' => $this->status,
            'sent_at' => optional($this->sent_at)->toIso8601String(),
            'responded_at' => optional($this->responded_at)->toIso8601String(),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            'user' => new UserResource($this->whenLoaded('user'))
        ];
    }
}
