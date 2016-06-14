<?php

namespace App\Http\Transformers\v1\Interaction;

use App\Http\Transformers\v1\RegionTransformer;
use App\Models\v1\Interaction\Site;
use League\Fractal\TransformerAbstract;

class SiteTransformer extends TransformerAbstract
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
     * @param Site $site
     * @return array
     */
    public function transform(Site $site)
    {
        $site->load('author');

        $array = [
            'id'              => $site->id,
            'author_id'       => $site->author_id,
            'author_type'     => $site->author_type,
            'author_name'     => $site->author->name,
            'region_id'       => $site->region_id,
            'call_sign'       => $site->call_sign,
            'site_type'       => $site->site_type,
            'total_reached'   => (int) $site->total_reached,
            'total_decisions' => (int) $site->total_decisions,
            'lat'             => $site->lat,
            'long'            => $site->long,
            'created_at'      => $site->created_at->toDateTimeString(),
            'updated_at'      => $site->updated_at->toDateTimeString(),
            'links'           => [
                [
                    'rel' => 'self',
                    'uri' => '/interactions/sites/' . $site->id,
                ]
            ]
        ];

        return $array;
    }

    /**
     * Include Region
     *
     * @param Site $site
     * @return \League\Fractal\Resource\Item
     */
    public function includeRegion(Site $site)
    {
        $region = $site->region;

        return $this->item($region, new RegionTransformer);
    }
}