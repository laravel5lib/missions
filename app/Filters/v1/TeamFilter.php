<?php namespace App\Filters\v1;

use Carbon\Carbon;

class TeamFilter extends Filter
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
        'callsign', 'created_at', 'updated_at'
    ];

    /**
     * Fields that are searchable.
     *
     * @var array
     */
    public $searchable = ['callsign'];
}