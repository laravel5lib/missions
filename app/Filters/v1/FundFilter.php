<?php namespace App\Filters\v1;

use EloquentFilter\ModelFilter;

class FundFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public function name($name)
    {
        return $this->where('name', 'LIKE', "%$name%");
    }

    public function minBalance($amount)
    {
        return $this->where('balance', '>=', $amount);
    }

    public function maxBalance($amount)
    {
        return $this->where('balance', '<=', $amount);
    }

    public function type($type)
    {
        return $this->where('fundable_type', str_plural($type));
    }
}
