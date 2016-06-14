<?php namespace App\Filters\v1;

use EloquentFilter\ModelFilter;

class UploadFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [method1, method2]]
    *
    * @var array
    */
    public $relations = [];
}