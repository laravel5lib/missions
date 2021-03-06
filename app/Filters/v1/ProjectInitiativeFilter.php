<?php namespace App\Filters\v1;

class ProjectInitiativeFilter extends Filter
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
    public $sortable = ['type', 'started_at', 'ended_at', 'created_at'];

    /**
     * Default searchable fields.
     *
     * @var array
     */
    public $searchable = ['type'];

    /**
     * Filter by country code.
     *
     * @param $code
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function country($code)
    {
        return $this->where('country_code', $code);
    }

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
        return $this->past();
    }
}
