<?php namespace App\Filters\v1;

use EloquentFilter\ModelFilter;

class CampaignFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [method1, method2]]
    *
    * @var array
    */
    public $relations = [
        'tags' => ['tags']
    ];

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
     * Filter by country
     *
     * @param $country
     * @return mixed
     */
    public function country($country)
    {
        return $this->where('countries', 'LIKE', "%$country%");
    }

    /**
     * Find by search
     *
     * @param $search
     * @return mixed
     */
    public function search($search)
    {
        return $this->where(function($q) use ($search)
        {
            return $q->where('name', 'LIKE', "%$search%");
        });
    }

    /**
     * Sort by fields
     *
     * @param $sort
     * @return mixed
     */
    public function sort($sort)
    {
        $sortable = [
            'name', 'created_at', 'updated_at'
        ];

        $param = preg_split('/\|+/', $sort);
        $field = $param[0];
        $direction = isset($param[1]) ? $param[1] : 'asc';

        if ( in_array($field, $sortable) )
            return $this->orderBy($field, $direction);

        return $this;
    }
}