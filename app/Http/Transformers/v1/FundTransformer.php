<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Fund;
use League\Fractal\TransformerAbstract;

class FundTransformer extends TransformerAbstract {

    /**
     * Transform the object into a basic array
     *
     * @param Fund $fund
     * @return array
     */
    public function transform(Fund $fund)
    {
        $fund->load('tagged');

        $array = [
            'id'         => $fund->id,
            'name'       => $fund->name,
            'balance'    => $fund->balance,
            'type'       => str_singular($fund->fundable_type),
            'class'      => $fund->class,
            'item'       => $fund->item,
            'slug'       => $fund->slug,
            'created_at' => $fund->created_at->toDateTimeString(),
            'updated_at' => $fund->updated_at->toDateTimeString(),
            'deleted_at' => $fund->deleted_at ? $fund->deleted_at->toDateTimeString() : null,
            'tags'       => $fund->tagNames(),
            'links'      => [
                [
                    'rel' => 'self',
                    'uri' => url('/api/funds/' . $fund->id),
                ]
            ]
        ];

        return $array;
    }
}