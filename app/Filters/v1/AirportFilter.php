<?php namespace App\Filters\v1;

use EloquentFilter\ModelFilter;

class AirportFilter extends ModelFilter
{
    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relatedModel => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

    /**
     * Fields that can be sorted.
     *
     * @var array
     */
    public $sortable = ['name', 'country'];

    /**
     * Fields that can be searched.
     *
     * @var array
     */
    // public $searchable = ['name', 'iata'];

    public function search($search)
    {
        return $this->where(function ($q) use ($search) {
            return $q
                ->where('name', 'LIKE', strtolower("%$search%"))
                ->orWhere('iata', 'LIKE', strtolower("%$search%"));
        });
    }
}