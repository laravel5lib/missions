<?php namespace App\Filters\v1;

class DeadlineFilter extends Filter
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
    public $sortable = ['name', 'date', 'grace_period'];

    /**
     * Default searchable fields.
     *
     * @var array
     */
    public $searchable = ['name'];

    /**
     * Filter by assignment.
     *
     * @param $assignment
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function assignment($assignment)
    {
        $param = preg_split('/\|+/', $assignment);

        if (isset($param[1])) {
            $this->where('deadline_assignable_id', $param[1]);
        }

        return $this->where('deadline_assignable_type', $param[0]);
    }
}
