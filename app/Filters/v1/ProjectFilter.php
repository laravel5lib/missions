<?php namespace App\Filters\v1;

class ProjectFilter extends Filter
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
    public $sortable = ['name', 'created_at'];

    /**
     * Default searchable fields.
     *
     * @var array
     */
    public $searchable = ['name'];

    /**
     * Is currently active.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function current()
    {
        return $this->new();
    }

    /**
     * Is archived.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function archived()
    {
        return $this->old();
    }

    /**
     * Is funded.
     *
     * @return mixed
     */
    public function funded()
    {
        return $this->funded();
    }
}
