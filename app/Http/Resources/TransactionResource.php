<?php

namespace App\Http\Resources;

use App\Http\Resources\FundResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'id'          => $this->id,
            'amount'      => $this->amountInDollars(),
            'anonymous'   => $this->anonymous,
            'type'        => $this->type,
            'description' => $this->description,
            'details'     => $this->details,
            'created_at'  => $this->created_at->toIso8601String(),
            'updated_at'  => $this->updated_at->toIso8601String(),
            'fund'        => new FundResource($this->whenLoaded('fund')),
            'donor'       => new DonorResource($this->whenLoaded('donor'))
        ];
    }
}
