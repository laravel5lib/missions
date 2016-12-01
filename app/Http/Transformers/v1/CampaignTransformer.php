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
        $campaign->load('avatar', 'banner', 'groups');

        $array = [
            'id'           => $campaign->id,
            'name'         => $campaign->name,
            'country'      => country($campaign->country_code),
            'description'  => $campaign->short_desc,
            'page_url'     => $campaign->page_url,
            'page_src'     => $campaign->page_src,
            'avatar'       => $campaign->avatar ? image($campaign->avatar->source) : url('/images/placeholders/campaign-placeholder.png'),
            'banner'       => $campaign->banner ? image($campaign->banner->source) : null,
            'started_at'   => $campaign->started_at->toDateString(),
            'ended_at'     => $campaign->ended_at->toDateString(),
            'status'       => $campaign->status,
            'groups_count' => $campaign->groups->count(),
            'published_at' => $campaign->published_at ? $campaign->published_at->toDateTimeString() : null,
            'created_at'   => $campaign->created_at->toDateTimeString(),
            'updated_at'   => $campaign->updated_at->toDateTimeString(),
            'links'        => [
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