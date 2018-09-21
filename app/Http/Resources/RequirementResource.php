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
            'id'                 => $this->id,
            'name'               => $this->name,
            'document_type'      => $this->document_type,
            'short_desc'         => $this->short_desc,
            'upfront'            => $this->upfront,
            'due_at'             => optional($this->due_at)->toIso8601String(),
            'grace_period'       => (int) $this->grace_period,
            'requester'          => [ 'id' => $this->requester_id, 'type' => $this->requester_type ],
            'created_at'         => $this->created_at->toIso8601String(),
            'updated_at'         => $this->updated_at->toIso8601String(),
            'updated_at'         => $this->whenPivotLoaded('requireables', function () {
                return $this->pivot->updated_at->toIso8601String();
            }),
            'custom'             => $this->isCustom($request),
            'groups_count'       => $this->groups_count,
            'trips_count'        => $this->trips_count,
            'reservations_count' => $this->reservations_count,
            'status' => $this->whenPivotLoaded('requireables', function () {
                return $this->pivot->status ?? 'incomplete';
            }),
            'rules' => $this->rules
        ];
    }

    private function isCustom($request)
    {
        return $this->requester_id === $request->route('requireableId') 
            && $this->requester_type === $request->route('requireableType');
    }
}
