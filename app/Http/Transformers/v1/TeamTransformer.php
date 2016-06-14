<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Team;
use League\Fractal\TransformerAbstract;

class TeamTransformer extends TransformerAbstract {

    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'region', 'translators'
    ];

    /**
     * Transform the object into a basic array
     *
     * @param Team $team
     * @return array
     */
    public function transform(Team $team)
    {
        $team->load('members', 'translators');

        return [
            'id'           => $team->id,
            'call_sign'    => $team->call_sign,
            'members'      => $team->members()->count(),
            'translators'  => $team->translators()->count(),
            'published_at' => $team->published_at ? $team->published_at->toDateTimeString() : null,
            'created_at'   => $team->created_at->toDateTimeString(),
            'updated_at'   => $team->updated_at->toDateTimeString(),
            'links'        => [
                [
                    'rel' => 'self',
                    'uri' => '/teams/' . $team->id,
                ]
            ]
        ];
    }

    /**
     * Include Region
     *
     * @param Team $team
     * @return \League\Fractal\Resource\Item
     */
    public function includeRegion(Team $team)
    {
        $region = $team->region;

        return $this->item($region, new RegionTransformer);
    }

    /**
     * Include Translators
     *
     * @param Team $team
     * @return \League\Fractal\Resource\Collection
     */
    public function includeTranslators(Team $team)
    {
        $translators = $team->translators;

        return $this->collection($translators, new TranslatorTransformer);
    }
}