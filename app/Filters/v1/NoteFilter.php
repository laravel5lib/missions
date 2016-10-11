<?php namespace App\Filters\v1;

use EloquentFilter\ModelFilter;

class NoteFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public function type($type)
    {
        $param = preg_split('/\|+/', $type);

        if (isset($param[1])) {
            $this->where('noteable_id', $param[1]);
        }

        return $this->where('noteable_type', $param[0]);
    }
}
