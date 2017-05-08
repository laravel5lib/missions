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
            'given_names'      => $occupant->given_names,
            'surname'          => $occupant->surname,
            'age'              => $occupant->age,
            'gender'           => $occupant->gender,
            'status'           => $occupant->status,
            'room_leader'      => (bool) $occupant->pivot ? $occupant->pivot->room_leader : false,
            'created_at'       => $occupant->created_at->toDateTimeString(),
            'updated_at'       => $occupant->updated_at->toDateTimeString(),
            'links'            => [
                [
                    'rel' => 'self',
                    'uri' => '/rooms/' . $occupant->room_id . '/occupants/' . $occupant->id
                ]
            ]
        ];
    }
}