<?php

namespace App\Http\Resources;

use App\Http\Resources\CostResource;
use App\Http\Resources\PaymentResource;
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
            'amount' => $this->amount,
            'active_at' => optional($this->active_at)->toIso8601String(),
            'cost' => new CostResource($this->cost),
            'payments' => PaymentResource::collection($this->whenLoaded('payments')),
            'reservations_count' => $this->reservations()->count(),
            'custom' => $this->isCustom($request)
        ];
    }

    private function isCustom($request)
    {
        switch ($request->segment(2)) {
            case 'campaign-groups':
                return $this->model_id === $request->route('groupId');
                break;
            
            case 'trips':
                return $this->model_id === $request->route('tripId');
                break;
            
            case 'reservations':
                return $this->model_id === $request->route('reservationId');
                break;

            default:
                return false;
                break;
        }
    }
}
