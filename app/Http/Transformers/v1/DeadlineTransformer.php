<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Deadline;
use App\Models\v1\DeadlineTripPivot;
use League\Fractal\TransformerAbstract;

class DeadlineTransformer extends TransformerAbstract
{
    /**
     * List of resources that can be included
     *
     * @var array
     */
    protected $availableIncludes = [
        'reservations'
    ];

    /**
     * Transform the object into a basic array
     *
     * @param Deadline $deadline
     * @return array
     */
    public function transform(Deadline $deadline)
    {
        $array = [
            'id'           => $deadline->id,
            'name'         => $deadline->name,
            'date'         => $deadline->date->toDateTimeString(),
            'grace_period' => (int) $deadline->grace_period,
            'enforced'     => (bool) $deadline->enforced
        ];

        if ($deadline->pivot) {
            $array = [
                'id'           => $deadline->id,
                'name'         => $deadline->name,
                'date'         => $deadline->date->toDateTimeString(),
                'grace_period' => $deadline->pivot->grace_period,
                'enforced'     => (bool) $deadline->pivot->enforced,
                'updated_at'   => $deadline->pivot->updated_at->toDateTimeString()
            ];
        }

        $array['links'] = [
            [
                'rel' => 'self',
                'uri' => '/api/deadlines/' . $deadline->id,
            ]
        ];

        return $array;
    }

    /**
     * Include Reservations
     *
     * @param Deadline $deadline
     * @return \League\Fractal\Resource\Item
     */
    public function includeReservations(Deadline $deadline)
    {
        $reservations = $deadline->reservations;

        return $this->collection($reservations, new ReservationTransformer);
    }
}
