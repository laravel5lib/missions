<?php namespace App\Filters\v1;

class PromocodeFilter extends Filter
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
    public $sortable = [
        'code'
    ];

    /**
     * Fields that can be searched.
     *
     * @var array
     */
    public $searchable = [
        'code', 'reservations.given_names', 'reservations.surname'
    ];

    public function promotional($id)
    {
        return $this->where('promotional_id', $id);
    }

    public function withDeactivated()
    {
        return $this->withTrashed();
    }
}
