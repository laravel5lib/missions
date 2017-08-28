<?php

namespace App\Services\Importers;

class CampaignListImportHandler extends ImportHandler
{

    /**
     * The model class to use
     *
     * @var string
     */
    public $model = 'App\Models\v1\Campaign';

    /**
     * The database columns and document
     * columns to find matches on.
     *
     * @var array
     */
    public $duplicates = ['name' => 'name', 'country_code' => 'country_code'];


    /**
     * Match Database Columns to Collection Properties.
     *
     * @param  Collection $campaign
     * @return Array
     */
    public function match_columns_to_properties($campaign)
    {
        $campaign = $campaign->transform(function ($item) {
            return trim($item);
        });

        return [
            'name' => $campaign->name,
            'country_code' => strtolower($campaign->country_code),
            'short_desc' => $campaign->short_desc,
            'started_at' => $campaign->started_at,
            'ended_at' => $campaign->ended_at,
            'published_at' => $campaign->published_at,
            'created_at' => $campaign->created_at,
            'updated_at' => $campaign->updated_at,
            'slug' => [
                'url' => $campaign->url ? $campaign->url : generate_slug($campaign->name)
            ]
        ];
    }
}
