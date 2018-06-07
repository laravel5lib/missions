<?php

namespace App\Http\Resources;

use App\Http\Resources\TripResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
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
            'id'                  => $this->id,
            'given_names'         => $this->given_names,
            'surname'             => $this->surname,
            'gender'              => $this->gender,
            'status'              => $this->status,
            'shirt_size'          => strtoupper($this->shirt_size),
            'shirt_size_name'     => shirtSize($this->shirt_size),
            'age'                 => $this->age,
            'birthday'            => $this->birthday->toDateString(),
            'email'               => $this->email,
            'phone_one'           => $this->phone_one,
            'phone_two'           => $this->phone_two,
            'address'             => $this->address,
            'city'                => $this->city,
            'state'               => $this->state,
            'zip'                 => $this->zip,
            'country_code'        => $this->country_code,
            'country_name'        => country($this->country_code),
            'companion_limit'     => (int) $this->companion_limit,
            'arrival_designation' => $this->designation 
                ? implode('', array_flatten($this->designation->content)) 
                : 'none',
            'avatar'              => $this->avatar 
                ? image($this->avatar->source) 
                : url('/images/placeholders/user-placeholder.png'),
            'desired_role'        => [
                'code' => $this->desired_role,
                'name' => teamRole($this->desired_role)
            ],
            'total_cost'          => $this->totalCostInDollars(),
            'total_raised'        => $this->totalRaisedInDollars(),
            'percent_raised'      => (int) $this->getPercentRaised(),
            'total_owed'          => $this->totalOwedInDollars(),
            'current_rate'        => $this->getCurrentRate() ? $this->getCurrentRate()->cost->name : 'N/A',
            'rate_locked'         => $this->getCurrentRate() ? (boolean) $this->getCurrentRate()->pivot->locked : false,
            'upcoming_deadline'   => $this->getUpcomingDeadline() 
                ? $this->getUpcomingDeadline()->format('M j, h:i a') 
                : 'N/A',
            'grace_days'          => $this->getUpcomingPayment() ? (int) $this->getUpcomingPayment()->grace_days : 'N/A',
            'created_at'          => $this->created_at->toDateTimeString(),
            'updated_at'          => $this->updated_at->toDateTimeString(),
            'deleted_at'          => $this->deleted_at ? $this->deleted_at->toDateTimeString() : null,
            'trip' => new TripResource($this->whenLoaded('trip'))
        ];
    }
}
