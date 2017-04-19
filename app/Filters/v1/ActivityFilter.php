<?php namespace App\Filters\v1;

class ActivityFilter extends Filter
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
    public $sortable = ['occured_at', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * Default searchable fields.
     *
     * @var array
     */
    public $searchable = ['name'];

    /**
     * At given date and after.
     *
     * @param $date
     */
    public function starts($date)
    {
        $this->whereDate('occured_at', '>=', $date);
    }

    /**
     * At given date and before.
     *
     * @param $date
     */
    public function ends($date)
    {
        $this->whereDate('occured_at', '<=', $date);
    }
}
