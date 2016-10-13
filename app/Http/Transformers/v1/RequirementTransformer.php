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
            'id'            => $requirement->id,
            'name'          => $requirement->name,
            'document_type' => $requirement->document_type,
            'short_desc'    => $requirement->short_desc,
            'due_at'        => $requirement->due_at->toDateTimeString(),
            'grace_period'  => (int) $requirement->grace_period,
            'created_at'    => $requirement->created_at->toDateTimeString(),
            'updated_at'    => $requirement->updated_at->toDateTimeString()
        ];

        if ($requirement->pivot)
        {
                $array['grace_period'] = (int) $requirement->pivot->grace_period
                    ? $requirement->pivot->grace_period
                    : $requirement->grace_period;
                $array['status'] = $requirement->pivot->status;
                $array['updated_at'] = $requirement->pivot->updated_at->toDateTimeString();
        }

        $array['links'] = [
            [
                'rel' => 'self',
                'uri' => '/api/'
                    . $requirement->requester_type
                    . '/' . $requirement->requester_id
                    . '/requirements/'
                    . $requirement->id,
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