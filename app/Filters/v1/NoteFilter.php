<?php namespace App\Filters\v1;

class NoteFilter extends Filter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    /**
     * The fields that are sortable.
     *
     * @var array
     */
    public $sortable = [
        'subject', 'created_at', 'updated_at'
    ];

    /**
     * The fields that are searchable.
     *
     * @var array
     */
    public $searchable = [
        'subject', 'content'
    ];

    /**
     * By noteable_type and noteable_id.
     *
     * @param $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function type($type)
    {
        $param = preg_split('/\|+/', $type);

        if (isset($param[1])) {
            $this->where('noteable_id', $param[1]);
        }

        return $this->where('noteable_type', $param[0]);
    }
}
