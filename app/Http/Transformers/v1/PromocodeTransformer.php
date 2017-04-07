<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Promocode;
use League\Fractal\TransformerAbstract;

class PromocodeTransformer extends TransformerAbstract {

    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'rewardable'
    ];

    /**
     * Transform the object into a basic array
     *
     * @param Promocode $promocode
     * @return array
     */
    public function transform(Promocode $promocode)
    {
        $data = [
            'id'               => $promocode->id,
            'code'             => $promocode->code,
            'use_count'        => (int) 0,
            'created_at'       => $promocode->created_at->toDateTimeString(),
            'updated_at'       => $promocode->updated_at->toDateTimeString(),
            'deleted_at'       => $promocode->deleted_at ? $promocode->deleted_at->toDateTimeString() : null,
        ];

        return $data;
    }

    public function includeRewardable(Promocode $promocode) {
        $affiliate = $promocode->rewardable;

        if (! $affiliate) return null;

        return $this->item($affiliate, new ReservationTransformer);
    }
}