<?php namespace App\Filters\v1;

class CampaignFilter extends Filter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [method1, method2]]
    *
    * @var array
    */
    public $relations = [];

    /**
     * Fields that can be sorted.
     *
     * @var array
     */
    public $sortable = ['name', 'created_at', 'updated_at'];

    /**
     * Fields that can be searched.
     *
     * @var array
     */
    public $searchable = ['name'];

    /**
     * Filter by has trips or not
     *
     * @param $hasTrips
     * @return mixed
     */
    public function hasTrips($hasTrips)
    {
        return $hasTrips == 'yes' ?
            $this->has('trips') :
            $this->has('trips', '<', 1);
    }

    /**
     * Show Only Published Campaigns
     * 
     * @return mixed
     */
    public function published()
    {
        return $this->public();
    }

    /**
     * Show Only Current Campaigns
     *
     * @return mixed
     */
    public function current()
    {
        return $this->active();
    }

    /**
     * Filter by country
     *
     * @param $country
     * @return mixed
     */
    public function country($country)
    {
        return $this->where('countries', 'LIKE', "%$country%");
    }
}