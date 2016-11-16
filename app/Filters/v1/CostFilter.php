<?php namespace App\Filters\v1;

class CostFilter extends Filter
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
    public $sortable = ['name', 'amount', 'active_at'];

    /**
     * Default searchable fields.
     *
     * @var array
     */
    public $searchable = ['name', 'amount', 'description'];

    /**
     * Filter by type.
     *
     * @param $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function type($type)
    {
        return $this->where('type', $type);
    }

    /**
     * Filter by cost assignment type.
     *
     * @param $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function assignment($type) {
        $param = preg_split('/\|+/', $type);

        if (isset($param[1])) {
            $this->where('cost_assignable_id', $param[1]);
        }

        return $this->where('cost_assignable_type', $param[0]);
    }
}
