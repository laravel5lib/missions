<?php namespace App\Filters\v1;

class PassengerFilter extends Filter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [method1, method2]]
    *
    * @var array
    */
    public $relations = [
        'reservation' => ['gender', 'status', 'age', 'role', 'hasCompanions', 'search', 'designation', 'groups', 'region']
    ];

    /**
     * Fields that can be sorted.
     *
     * @var array
     */
    public $sortable = ['created_at', 'updated_at', 'seat_no'];

    /**
     * Fields that can be searched.
     *
     * @var array
     */
    public $searchable = [];
}
