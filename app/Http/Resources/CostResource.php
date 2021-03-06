<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CostResource extends JsonResource
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
            'name' => $this->name,
            'type' => $this->getUserFriendlyLabel($this->type),
            'description' => $this->description,
            'reservations_count' => $this->reservationsCount()
        ];
    }

    private function getUserFriendlyLabel($type)
    {
        $labels = [
            'incremental' => 'Registration',
            'optional' => 'Rooming',
            'static' => 'Additional',
            'upfront' => 'Upfront',
            'fee' => 'Late Fee'
        ];

        return $labels[$type];
    }
}
