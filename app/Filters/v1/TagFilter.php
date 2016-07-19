<?php namespace App\Filters\v1;

use EloquentFilter\ModelFilter;

class TagFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [method1, method2]]
    *
    * @var array
    */
    public $relations = [];

    public function tags($names)
    {
        return $this->whereIn('name', $names);
    }
}