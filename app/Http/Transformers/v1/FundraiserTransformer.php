<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Fundraiser;
use League\Fractal\TransformerAbstract;

class FundraiserTransformer extends TransformerAbstract
{
    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = [];

    /**
     * Turn this item object into a generic array
     *
     * @param Fundraiser $fundraiser
     * @return array
     */
    public function transform(Fundraiser $fundraiser)
    {
        $fundraiser->load('banner');

        $array = [
            'id'            => $fundraiser->id,
            'name'          => $fundraiser->name,
            'goal_amount'   => $fundraiser->goal_amount,
            'raised_amount' => (int) $fundraiser->raised() / 100,
            'banner'        => $fundraiser->banner ? image($fundraiser->banner->source) : null,
            'description'   => $fundraiser->description,
            'expires_at'    => $fundraiser->expires_at ? $fundraiser->expires_at->toDateTimeString() : null,
            'created_at'    => $fundraiser->created_at->toDateTimeString(),
            'updated_at'    => $fundraiser->updated_at->toDateTimeString(),
            'links'         => [
                [
                    'rel' => 'self',
                    'uri' => '/fundraisers/' . $fundraiser->id,
                ]
            ]
        ];

        return $array;
    }

}