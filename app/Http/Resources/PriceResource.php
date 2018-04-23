<?php

namespace App\Http\Resources;

use App\Http\Resources\CostResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PriceResource extends JsonResource
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
            'id' => $this->uuid,
            'amount' => (double) $this->amount/100,
            'active_at' => optional($this->active_at)->toIso8601String(),
            'cost' => new CostResource($this->cost)
        ];
    }
}
