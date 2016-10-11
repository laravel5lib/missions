<?php namespace App\Filters\v1;

class TodoFilter extends Filter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    /**
     * The fields that can be sorted.
     *
     * @var array
     */
    public $sortable = ['task', 'completed_at'];

    /**
     * The fields that can be searched.
     *
     * @var array
     */
    public $searchable = ['task', 'user.name'];

    /**
     * By todoable_type and todoable_id.
     *
     * @param $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function type($type)
    {
        $param = preg_split('/\|+/', $type);

        if (isset($param[1])) {
            $this->where('todoable_id', $param[1]);
        }

        return $this->where('todoable_type', $param[0]);
    }

    /**
     * By status.
     *
     * @param $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function status($status)
    {
        return $status == 'complete' ? $this->whereNotNull('completed_at') : $this->where('completed_at', null);
    }
}
