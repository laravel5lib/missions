<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DonorResource extends JsonResource
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
            'first_name'    => $this->first_name,
            'last_name'     => $this->last_name,
            'company'       => $this->company,
            'email'         => $this->email,
            'phone'         => $this->phone,
            'address'       => $this->address,
            'city'          => $this->city,
            'state'         => $this->state,
            'zip'           => $this->zip,
            'country'       => [
                'code' => strtolower($this->country_code),
                'name' => country($this->country_code),
            ],
            'customer_id'   => $this->customer_id,
            'created_at'    => $this->created_at->toIso8601String(),
            'updated_at'    => $this->updated_at->toIso8601String(),
            'deleted_at'    => optional($this->deleted_at)->toIso8601String()
        ];
    }
}
