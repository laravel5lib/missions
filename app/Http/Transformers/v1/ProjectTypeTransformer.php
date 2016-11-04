<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\ProjectType;
use League\Fractal;

class ProjectTypeTransformer extends Fractal\TransformerAbstract
{

    /**
     * Turn this item object into a generic array
     *
     * @param ProjectType $type
     * @return array
     */
    public function transform(ProjectType $type)
    {
        $type->load('image');

        return [
            'id'             => $type->id,
            'name'           => $type->name,
            'country'        => [
                'code' => $type->country_code,
                'name' => country($type->country_code)
            ],
            'short_desc'     => $type->short_desc,
//            'image'         => $type->image ? image($type->image->source) : null,
            'projects_count' => $type->projects_count,
            'created_at'     => $type->created_at->toDateTimeString(),
            'updated_at'     => $type->updated_at->toDateTimeString(),
            'links'          => [
                [
                    'rel' => 'self',
                    'uri' => url('/api/causes/' . $type->project_cause_id . '/types/' . $type->id),
                ]
            ]
        ];
    }
}