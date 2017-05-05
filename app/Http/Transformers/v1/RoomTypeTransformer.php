<?php

namespace App\Http\Transformers\v1;
use App\Models\v1\RoomType;
use League\Fractal\TransformerAbstract;

class RoomTypeTransformer extends TransformerAbstract
{

    /**
     * Transform the object into a basic array
     *
     * @param RoomType $type
     * @return array
     */
    public function transform(RoomType $type)
    {
        return [
            'id'         => $type->id,
            'name'       => $type->name,
            'rules'      => $type->rules,
            'created_at' => $type->created_at->toDateTimeString(),
            'updated_at' => $type->updated_at->toDateTimeString(),
            'deleted_at' => $type->deleted_at ? $type->deleted_at->toDateTimeString() : null,
            'links'      => [
                [
                    'rel' => 'self',
                    'uri' => '/api/rooming/types/' . $type->id,
                ]
            ]
        ];
    }
}