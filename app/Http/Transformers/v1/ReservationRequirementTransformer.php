<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\ReservationRequirement;
use League\Fractal\TransformerAbstract;

class ReservationRequirementTransformer extends TransformerAbstract
{
    /**
     * Transform the object into a basic array
     *
     * @param ReservationRequirement $r
     * @return array
     */
    public function transform(ReservationRequirement $r)
    {
        $array = [
            'id'             => $r->id,
            'name'           => $r->requirement->name,
            'document_type'  => $r->document_type,
            'document_id'    => $r->document_id,
            'reservation_id' => $r->reservation_id,
            'requirement_id' => $r->requirement_id,
            'short_desc'     => $r->requirement->short_desc,
            'due_at'         => $r->requirement->due_at->toDateTimeString(),
            'status'         => $r->status,
            'grace_period'   => (int) $r->grace_period,
            'completed_at'   => $r->completed_at ? $r->completed_at->toDateTimeString() : null,
            'created_at'     => $r->created_at->toDateTimeString(),
            'updated_at'     => $r->updated_at->toDateTimeString()
        ];

        $array['links'] = [
            [
                'rel' => 'self',
                'uri' => '/api/requirements/' . $r->requirement_id,
            ]
        ];

        return $array;
    }
}