<?php namespace App\Filters\v1;

class FundFilter extends Filter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public $sortable = ['name', 'balance'];

    public function minBalance($amount)
    {
        if (! $amount) return null;

        return $this->where('balance', '>=', $amount);
    }

    public function maxBalance($amount)
    {
        if (! $amount) return null;

        return $this->where('balance', '<=', $amount);
    }

    public function type($type)
    {
        return $this->where('fundable_type', str_plural($type));
    }

    public function search($terms)
    {
        return $this->where('name', 'LIKE', "%$terms%");
    }
}
