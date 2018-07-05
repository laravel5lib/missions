<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class SquadMemberResource extends JsonResource
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
            'reservation_id' => $this->reservation_id,
            'surname' => $this->surname,
            'given_names' => $this->given_names,
            'gender' => $this->gender,
            'status' => $this->status,
            'birthday' => $this->birthday ? Carbon::parse($this->birthday)->toDateString() : null,
            'age' => $this->birthday ? Carbon::parse($this->birthday)->diffInYears() : null,
            'role' => [
                'code' => $this->role,
                'name' => teamRole($this->role),
            ],
            'squad_callsign' => $this->callsign,
            'squad_group' => $this->group,
            'region_name' => $this->region_name,
            'organization_name' => $this->organization_name,
            'trip_type' => $this->trip_type,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String()
        ];
    }
}
