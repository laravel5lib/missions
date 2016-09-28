<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Requirement;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class RequirementTransformer extends TransformerAbstract
{
    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'reservations'
    ];

    /**
     * Transform the object into a basic array
     *
     * @param Requirement $requirement
     * @return array
     */
    public function transform(Requirement $requirement)
    {
        $array = [
            'id'           => $requirement->id,
            'item'         => $requirement->item,
            'due_at'       => $requirement->due_at->toDateTimeString(),
            'grace_period' => (int) $requirement->grace_period,
            'enforced'     => (bool) $requirement->enforced,
            'links'        => [
                [
                    'rel' => 'self',
                    'uri' => '/requirements/' . $requirement->id,
                ]
            ]
        ];

        if ($requirement->pivot)
        {
            $array = [
                'requirement_id' => $requirement->id,
                'item'           => $requirement->item,
                'item_type'      => $requirement->item_type,
                'due_at'         => $requirement->due_at->toDateTimeString(),
                'grace_period'   => (int) $requirement->pivot->grace_period ? $requirement->pivot->grace_period : $requirement->grace_period,
                'enforced'       => (bool) $requirement->enforced,
                'status'         => $requirement->pivot->status,
                'updated_at'     => $requirement->pivot->updated_at->toDateTimeString()
            ];
        }

        $array['links'] = [
            [
                'rel' => 'self',
                'uri' => '/requirements/' . $requirement->id,
            ]
        ];

        return $array;
    }

    /**
     * Include Reservation
     *
     * @param Requirement $requirement
     * @return \League\Fractal\Resource\Item
     */
    public function includeReservations(Requirement $requirement)
    {
        $reservations = $requirement->reservations;

        return $this->collection($reservations, new ReservationTransformer);
    }
}