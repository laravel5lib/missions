<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Promotional;
use League\Fractal\TransformerAbstract;

class PromotionalTransformer extends TransformerAbstract
{

    /**
     * Transform the object into a basic array
     *
     * @param Promotional $promotional
     * @return array
     */
    public function transform(Promotional $promotional)
    {
        $data = [
            'id'               => $promotional->id,
            'name'             => $promotional->name,
            'reward'           => $promotional->getRewardInDollars(),
            'expires_at'       => $promotional->expires_at ? $promotional->expires_at->toDateTimeString() : null,
            'promoteable_id'   => $promotional->promoteable_id,
            'promoteable_type' => $promotional->promoteable_type,
            'promocodes_count' => $promotional->promocodes_count,
            'created_at'       => $promotional->created_at->toDateTimeString(),
            'updated_at'       => $promotional->updated_at->toDateTimeString(),
            'deleted_at'       => $promotional->deleted_at ? $promotional->deleted_at->toDateTimeString() : null,
        ];

        return $data;
    }
}
