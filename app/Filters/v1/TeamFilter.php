<?php namespace App\Filters\v1;

use Carbon\Carbon;

class TeamFilter extends Filter
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
    public $sortable = [
        'call_sign', 'published_at', 'created_at', 'updated_at'
    ];

    /**
     * Fields that are searchable.
     *
     * @var array
     */
    public $searchable = ['call_sign', 'region.name'];

    /**
     * By published.
     * @return mixed
     */
    public function published()
    {
        return $this->whereNotNull('published_at')
                    ->where('published_at', '<=', Carbon::now());
    }

    /**
     * Filter by regions
     *
     * @param $ids
     * @return mixed
     */
    public function regions($ids)
    {
        return $this->whereIn('region_id', $ids);
    }

    /**
     * By member range.
     *
     * @param $members
     * @return mixed
     */
    public function members($members)
    {
        if(count($members) < 2) return $this;

        return $this->has('members', '>=', $members[0])
                    ->has('members', '<=', $members[1]);
    }
}