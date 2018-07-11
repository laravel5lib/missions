<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RequirementResource extends JsonResource
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
            'document_type' => $this->document_type,
            'short_desc'    => $this->short_desc,
            'due_at'        => $this->due_at->toIso8601String(),
            'grace_period'  => (int) $this->grace_period,
            'requester'     => [ 'id' => $this->requester_id, 'type' => $this->requester_type ],
            'created_at'    => $this->created_at->toIso8601String(),
            'updated_at'    => $this->updated_at->toIso8601String()
        ];
    }
}