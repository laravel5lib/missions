<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\ProjectCause;
use League\Fractal;

class CauseTransformer extends Fractal\TransformerAbstract
{
    /**
     * Turn this item object into a generic array
     *
     * @param ProjectCause $cause
     * @return array
     */
    public function transform(ProjectCause $cause)
    {
        $cause->load('image');

        return [
            'id'            => $cause->id,
            'name'          => $cause->name,
            'short_desc'    => $cause->short_desc,
            'countries'     => $cause->countries,
            'image'         => $cause->image ? image($cause->image->source) : null,
            'created_at'    => $cause->created_at->toDateTimeString(),
            'updated_at'    => $cause->updated_at->toDateTimeString(),
            'links'         => [
                [
                    'rel' => 'self',
                    'uri' => url('/api/causes/'.$cause->id),
                ]
            ],
        ];
    }
}