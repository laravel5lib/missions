<?php namespace App\Filters\v1;

class ItineraryFilter extends Filter
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
    public $sortable = ['created_at', 'updated_at'];

    /**
     * Default searchable fields.
     *
     * @var array
     */
    public $searchable = ['name'];

    /**
     * By curator_type and curator_id.
     *
     * @param $curator
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function curator($curator)
    {
        $param = preg_split('/\|+/', $curator);

        if (isset($param[1])) {
            $this->where('curator_id', $param[1]);
        }

        return $this->where('curator_type', $param[0]);
    }
}
