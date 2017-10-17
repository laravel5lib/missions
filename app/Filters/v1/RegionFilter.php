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
        'name', 'country_code', 'callsign', 'created_at', 'updated_at'
    ];

    /**
     * Fields that can be searched.
     *
     * @var array
     */
    public $searchable = [
        'name', 'callsign'
    ];

    public function country($code)
    {
        return $this->whereCountryCode(trim(strtolower($code)));
    }
}
