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
    protected $availableIncludes = [
        'sponsor'
    ];

    /**
     * Turn this item object into a generic array
     *
     * @param Fundraiser $fundraiser
     * @return array
     */
    public function transform(Fundraiser $fundraiser)
    {
        $array = [
            'id'            => $fundraiser->id,
            'name'          => $fundraiser->name,
            'goal_amount'   => $fundraiser->goal_amount,
            'raised_amount' => (int) $fundraiser->raised() / 100,
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

    /**
     * Include Sponsor
     *
     * @param Fundraiser $fundraiser
     * @return \League\Fractal\Resource\Item
     */
    public function includeSponsor(Fundraiser $fundraiser)
    {
        $sponsor = $fundraiser->sponsor;

        dd(get_class($sponsor));

//        return $this->collection($sponsor, new UserTransformer);


    }
}