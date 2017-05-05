<?php namespace App\Filters\v1;

class HubFilter extends Filter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    /**
     * Default sortable fields.
     *
     * @var array
     */
    public $sortable = [];

    /**
     * Default searchable fields.
     *
     * @var array
     */
    public $searchable = [];
}
