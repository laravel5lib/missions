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
            'id'            => (int) $type->id,
            'name'          => $type->name,
            'short_desc'    => $type->short_desc,
            'image'         => $type->image ? image($type->image->source) : null,
            'active'        => (boolean) $type->active,
            'created_at'    => $type->created_at->toDateTimeString(),
            'updated_at'    => $type->updated_at->toDateTimeString(),
            'links'         => [
                [
                    'rel' => 'self',
                    'uri' => url('/api/projects/types/'.$type->id),
                ]
            ]
        ];
    }
}