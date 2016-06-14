<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Campaign;
use App\Utilities\v1\Country;
use League\Fractal\TransformerAbstract;

class CampaignTransformer extends TransformerAbstract
{
    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'trips', 'groups'
    ];

    /**
     * Turn this item object into a generic array
     *
     * @param Campaign $campaign
     * @return array
     */
    public function transform(Campaign $campaign)
    {
        $array = [
            'id'          => $campaign->id,
            'name'        => $campaign->name,
            'countries'   => $campaign->countries,
            'description' => $campaign->description,
            'created_at'  => $campaign->created_at->toDateTimeString(),
            'updated_at'  => $campaign->updated_at->toDateTimeString(),
            'links'       => [
                [
                    'rel' => 'self',
                    'uri' => '/campaigns/' . $campaign->id,
                ]
            ]
        ];

        return $array;
    }

    /**
     * Include Trips
     *
     * @param Campaign $campaign
     * @return \League\Fractal\Resource\Item
     */
    public function includeTrips(Campaign $campaign)
    {
        $trips = $campaign->trips;

        return $this->collection($trips, new TripTransformer);
    }

    /**
     * Include Groups
     *
     * @param Campaign $campaign
     * @return \League\Fractal\Resource\Item
     */
    public function includeGroups(Campaign $campaign)
    {
        $groups = $campaign->groups;

        return $this->collection($groups, new GroupTransformer);
    }
}