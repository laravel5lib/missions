<?php namespace App\Filters\v1;

use Carbon\Carbon;

class TeamMemberFilter extends Filter
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
    public $sortable = ['group', 'created_at', 'updated_at'];

    /**
     * Fields that can be searched.
     *
     * @var array
     */
    public $searchable = ['reservations.given_names', 'reservations.surname'];

    /**
     * By published.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function published()
    {
        return $this->whereHas('team', function ($team) {
            return $team->whereNotNull('published_at')
                       ->where('published_at', '<=', Carbon::now());
        });
    }

    /**
     * By teams.
     *
     * @param $ids
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function teams($ids)
    {
        return $this->whereIn('team_id', $ids);
    }

    /**
     * By roles.
     *
     * @param $ids
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function roles($ids)
    {
        return $this->whereIn('role_id', $ids);
    }

    /**
     * By Groups.
     *
     * @param $groups
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function groups($groups)
    {
        return $this->whereIn('group', $groups);
    }

    /**
     * Is a leader.
     *
     * @param $leader
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function leader($leader)
    {
        return $leader == 'yes' ?
            $this->where('leader', true) :
            $this->where('leader', false);
    }
}
