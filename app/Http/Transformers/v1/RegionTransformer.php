<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Region;
use League\Fractal\TransformerAbstract;

class RegionTransformer extends TransformerAbstract {

    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'campaign', 'teams', 'accommodations'
    ];

    /**
     * Transform the object into a basic array
     *
     * @param Region $region
     * @return array
     */
    public function transform(Region $region)
    {
        $region->load(['teams', 'accommodations']);

        return [
            'id'             => $region->id,
            'name'           => $region->name,
            'call_sign'      => $region->call_sign,
            'country_code'   => $region->country_code,
            'country_name'   => country($region->country_code),
            'campaign_id'    => $region->campaign_id,
            'teams'          => (int) $region->teams()->count(),
            'accommodations' => (int) $region->accommodations()->count(),
            'created_at'     => $region->created_at->toDateTimeString(),
            'updated_at'     => $region->updated_at->toDateTimeString(),
            'links'          => [
                [
                    'rel' => 'self',
                    'uri' => '/regions/' . $region->id,
                ]
            ]
        ];
    }

    /**
     * Include Campaign
     *
     * @param Region $region
     * @return \League\Fractal\Resource\Item
     */
    public function includeCampaign(Region $region)
    {
        $campaign = $region->campaign;

        return $this->item($campaign, new CampaignTransformer);
    }

    /**
     * Include Teams
     *
     * @param Region $region
     * @return \League\Fractal\Resource\Item
     */
    public function includeTeams(Region $region)
    {
        $teams = $region->teams;

        return $this->collection($teams, new TeamTransformer);
    }

    public function includeAccommodations(Region $region)
    {
        $accommodations = $region->accommodations;

        return $this->collection($accommodations, new AccommodationTransformer);
    }
}