<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Reservation;
use League\Fractal\TransformerAbstract;

class OccupantTransformer extends TransformerAbstract {

    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = ['companions', 'squads'];

    /**
     * Transform the object into a basic array
     *
     * @param Reservation $occupant
     * @return array
     */
    public function transform($occupant)
    {
        $data = [
            'id'               => $occupant->id,
            'avatar'           => $occupant->avatar ? image($occupant->avatar->source) : url('/images/placeholders/user-placeholder.png'),
            'given_names'      => $occupant->given_names,
            'surname'          => $occupant->surname,
            'age'              => $occupant->age,
            'gender'           => $occupant->gender,
            'desired_role'     => [
                'code' =>       $occupant->desired_role,
                'name' =>       teamRole($occupant->desired_role)
            ],
            'status'           => $occupant->status,
            'travel_group'        => $occupant->trip->group->name,
            'arrival_designation' => $occupant->designation ? 
                implode('', array_flatten($occupant->designation->content)) : 'none',
            'room_leader'      => (bool) $occupant->pivot->room_leader,
            'created_at'       => $occupant->created_at->toDateTimeString(),
            'updated_at'       => $occupant->updated_at->toDateTimeString(),
            'links'            => [
                [
                    'rel' => 'self',
                    'uri' => '/rooms/' . $occupant->pivot->room_id . '/occupants/' . $occupant->id
                ]
            ]
        ];

        if($occupant->pivot) {
            $data['relationship'] = $occupant->pivot->relationship;
        }

        return $data;
    }

    /**
     * Include Companions
     *
     * @param Reservation $occupant
     * @return \League\Fractal\Resource\Collection
     */
    public function includeCompanions(Reservation $occupant)
    {
        $companions = $occupant->companionReservations;

        return $this->collection($companions, new OccupantTransformer);
    }

    /**
     * Include Squads
     *
     * @param Reservation $occupant
     * @return \League\Fractal\Resource\Collection
     */
    public function includeSquads(Reservation $occupant)
    {
        $squads = $occupant->squads;

        return $this->collection($squads, new TeamSquadTransformer);
    }
}