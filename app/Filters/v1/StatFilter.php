<?php namespace App\Filters\v1;

class StatFilter extends Filter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [method1, method2]]
    *
    * @var array
    */
    public $relations = [];

    /**
     * Fields that can be sorted.
     *
     * @var array
     */
    public $sortable = [];

    /**
     * Fields that can be searched.
     *
     * @var array
     */
    public $searchable = [];

    /**
     * By region.
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function region($id)
    {
        return $this->whereHas('team', function ($team) use ($id) {
            $team->whereHas('region', function ($region) use ($id) {
                $region->where('id', $id);
            });
        });
    }

    /**
     * By team.
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function team($id)
    {
        return $this->where('team_id', $id);
    }
}
