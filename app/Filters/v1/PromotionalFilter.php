<?php namespace App\Filters\v1;

class PromotionalFilter extends Filter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public function promoterType($type)
    {
        return $this->where('promoteable_type', $type);
    }

    public function promoterId($id)
    {
        return $this->where('promoteable_id', $id);
    }

    public function withInactive()
    {
        return $this->withTrashed();
    }
}
