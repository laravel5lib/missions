<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RateResource extends JsonResource
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
            'amount' => (double) $this->amount/100,
            'type' => $this->type,
            'description' => $this->description,
            'active_at' => optional($this->active_at)->toIso8601String()
        ];
    }
}
