<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Requirement;
use League\Fractal\TransformerAbstract;

class RequirementTransformer extends TransformerAbstract
{
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

        $array['links'] = [
            [
                'rel' => 'self',
                'uri' => '/api/requirements/' . $requirement->id,
            ]
        ];

        return $array;
    }
}
