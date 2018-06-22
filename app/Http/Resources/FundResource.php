<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FundResource extends JsonResource
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
            'id'         => $this->id,
            'name'       => $this->name,
            'balance'    => $this->balanceInDollars(),
            'type'       => str_singular($this->fundable_type),
            'class'      => [
                'id'   => $this->accountingClass->id,
                'name' => $this->accountingClass->name
            ],
            'item'      =>  [
                'id'   => $this->accountingItem->id,
                'name' => $this->accountingItem->name
            ],
            'slug'       => $this->slug,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            'deleted_at' => optional($this->deleted_at)->toIso8601String()
        ];
    }
}
