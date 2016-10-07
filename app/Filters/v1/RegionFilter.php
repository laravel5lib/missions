<?php namespace App\Filters\v1;

class RegionFilter extends Filter
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
    public $sortable = [
        'name', 'country_code', 'call_sign', 'created_at', 'updated_at'
    ];

    /**
     * Fields that can be searched.
     *
     * @var array
     */
    public $searchable = [
        'name', 'call_sign', 'accommodations.name'
    ];

    /**
     * Filter by country code
     *
     * @param $codes
     * @return mixed
     */
    public function countries($codes)
    {
        return $this->whereIn('country_code', $codes);
    }

    /**
     * Between number of teams.
     *
     * @param $teams
     * @return mixed
     */
    public function teams($teams)
    {
        if(count($teams) < 2) return $this;

        return $this->has('teams', '>=', $teams[0])
                    ->has('teams', '<=', $teams[1]);
    }

    /**
     * If has accommodations.
     *
     * @param $hasAccommodations
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function hasAccommodations($hasAccommodations)
    {
        return $hasAccommodations == 'yes' ?
            $this->has('accommodations') :
            $this->has('accommodations', '<', 1);
    }
}