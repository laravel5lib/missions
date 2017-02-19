<?php namespace App\Filters\v1;

class QuestionnaireFilter extends Filter
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
    public $sortable = ['name', 'type', 'created_at', 'updated_at'];

    /**
     * Default searchable fields.
     *
     * @var array
     */
    public $searchable = ['name'];

    /**
     * By Type.
     *
     * @param $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function type($type)
    {
        return $this->where('type', $type);
    }
}
