<?php

namespace App\Http\Transformers\v1;

use League\Fractal\TransformerAbstract;

class OccupantTransformer extends TransformerAbstract {

    /**
     * Transform the object into a basic array
     *
     * @param Reservation $occupant
     * @return array
     */
    public function transform($occupant)
    {
        return [
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
            'room_leader'      => (bool) $occupant->pivot->room_leader,
            'created_at'       => $occupant->pivot->created_at->toDateTimeString(),
            'updated_at'       => $occupant->pivot->updated_at->toDateTimeString(),
            'links'            => [
                [
                    'rel' => 'self',
                    'uri' => '/rooms/' . $occupant->pivot->room_id . '/occupants/' . $occupant->id
                ]
            ]
        ];
    }
}