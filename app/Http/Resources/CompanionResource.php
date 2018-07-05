<?php

namespace App\Http\Resources;

use Illuminate\Support\Carbon;
use App\Http\Resources\TripResource;
use App\Http\Resources\SquadResource;
use App\Http\Resources\SquadMemberResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanionResource extends JsonResource
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
            'surname' => $this->surname,
            'given_names' => $this->given_names,
            'gender' => $this->gender,
            'age' => $this->birthday ? Carbon::parse($this->birthday)->diffInYears() : null,
            'status' => $this->status,
            'relationship' => $this->relationship,
            'role' => [
                'code' => $this->desired_role,
                'name' => teamRole($this->desired_role)
            ],
            'trip' => $this->when(
                $this->relationLoaded('companionReservation') &&
                $this->companionReservation->relationLoaded('trip'),
                function () {
                    return new TripResource($this->companionReservation->trip);
                }
            ),
            'squad' => $this->when(
                $this->relationLoaded('companionReservation') &&
                $this->companionReservation->relationLoaded('squadMemberships'),
                function () {
                    $member = $this->companionReservation->squadMemberships->first();
                    return [
                        'callsign' => $member ? $member->squad->callsign : null,
                        'group' => optional($member)->group,
                        'region' => $member ? $member->squad->region->name : null
                    ];
                }
            )
        ];
    }
}
