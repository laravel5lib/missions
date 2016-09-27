<?php namespace App\Filters\v1;

use EloquentFilter\ModelFilter;

class TransactionFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public function fund($id)
    {
        return $this->where('fund_id', $id);
    }

    public function donor($id)
    {
        return $this->where('donor_id', $id);
    }

    public function type($type)
    {
        return $this->where('type', $type);
    }

    public function minAmount($amount)
    {
        return $this->where('amount', '>=', $amount);
    }

    public function maxAmount($amount)
    {
        return $this->where('amount', '<=', $amount);
    }
}
