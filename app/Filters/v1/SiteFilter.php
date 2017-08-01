<?php namespace App\Filters\v1;

class SiteFilter extends Filter
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
        'call_sign', 'site_type', 'total_reached',
        'total_decisions', 'created_at', 'updated_at'
    ];

    /**
     * Fields that can be searched.
     *
     * @var array
     */
    public $searchable = [
        'call_sign', 'lat', 'long', 'region.name',
        'reservation.given_names', 'reservation.surname'
    ];

    /**
     * Find by types
     *
     * @param $types
     * @return mixed
     */
    public function types($types)
    {
        return $this->whereIn('site_type', $types);
    }

    /**
     * Find by total reached range
     *
     * @param $reached
     * @return $this
     */
    public function reached($reached)
    {
        if (count($reached) < 2) {
            return $this;
        }

        return $this->whereBetween('total_reached', $reached);
    }

    /**
     * Find by total decisions range
     *
     * @param $decisions
     * @return $this
     */
    public function decisions($decisions)
    {
        if (count($decisions) < 2) {
            return $this;
        }

        return $this->whereBetween('total_decisions', $decisions);
    }
}
