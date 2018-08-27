<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MedicalReleaseResource extends JsonResource
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
            'id'                => $this->id,
            'user_id'           => $this->user_id,
            'name'              => $this->name,
            'pregnant'          => (bool) $this->pregnant,
            'height'            => (int) $this->height,
            'weight'            => (int) $this->weight,
            'ins_provider'      => $this->ins_provider,
            'ins_policy_no'     => $this->ins_policy_no,
            'is_risk'           => (bool) $this->is_risk,
            'takes_medication'  => (bool) $this->takes_medication,
            'has_conditions'    => (bool) $this->conditions_count,
            'has_allergies'     => (bool) $this->allergies_count,
            'emergency_contact' => $this->emergency_contact,
            'created_at'        => $this->created_at->toIso8601String(),
            'updated_at'        => $this->updated_at->toIso8601String(),
            'conditions'        => MedicalConditionResource::collection($this->whenLoaded('conditions')),
            'allergies'         => MedicalAllergyResource::collection($this->whenLoaded('allergies')),
            'user'              => new UserResource($this->whenLoaded('user')),
            'uploads'           => MediaResource::collection($this->whenLoaded('uploads'))
        ];
    }
}
