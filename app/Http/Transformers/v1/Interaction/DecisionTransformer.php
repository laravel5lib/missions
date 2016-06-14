<?php

namespace App\Http\Transformers\v1\Interaction;

use App\Http\Transformers\v1\RegionTransformer;
use App\Http\Transformers\v1\ReservationTransformer;
use App\Models\v1\Interaction\Decision;
use League\Fractal\TransformerAbstract;

class DecisionTransformer extends TransformerAbstract
{
    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'region'
    ];

    /**
     * Turn this item object into a generic array
     *
     * @param Decision $decision
     * @return array
     */
    public function transform(Decision $decision)
    {
        $decision->load('author');

        $array = [
            'id'          => $decision->id,
            'author_id'   => $decision->author_id,
            'author_type' => $decision->author_type,
            'author_name' => $decision->author->name,
            'region_id'   => $decision->region_id,
            'name'        => $decision->name,
            'gender'      => $decision->gender,
            'age_group'   => $decision->age_group,
            'decision'    => (bool) $decision->decision,
            'email'       => $decision->email,
            'phone'       => $decision->phone,
            'lat'         => $decision->lat,
            'long'        => $decision->long,
            'created_at'  => $decision->created_at->toDateTimeString(),
            'updated_at'  => $decision->updated_at->toDateTimeString(),
            'links'       => [
                [
                    'rel' => 'self',
                    'uri' => '/interactions/decisions/' . $decision->id,
                ]
            ]
        ];

        return $array;
    }

    /**
     * Include Region
     *
     * @param Decision $decision
     * @return \League\Fractal\Resource\Item
     */
    public function includeRegion(Decision $decision)
    {
        $region = $decision->region;

        return $this->item($region, new RegionTransformer);
    }
}