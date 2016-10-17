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

    /**
     * Fields that can be sorted.
     *
     * @var array
     */
    public $sortable = ['name', 'balance'];

    /**
     * Fields that can be searched.
     *
     * @var array
     */
    public $searchable = ['name'];

    /**
     * From minimum balance amount.
     *
     * @param $amount
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function minBalance($amount)
    {
        return $this->where('balance', '>=', $amount);
    }

    /**
     * To maximum balance amount.
     *
     * @param $amount
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function maxBalance($amount)
    {
        return $this->where('balance', '<=', $amount);
    }

    /**
     * By type.
     *
     * @param $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function type($type)
    {
        return $this->where('fundable_type', str_plural($type));
    }
}
