<?php namespace App\Filters\v1;

use Carbon\Carbon;
use Dingo\Api\Auth\Auth;
use EloquentFilter\ModelFilter;

class TeamMemberFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [method1, method2]]
    *
    * @var array
    */
    public $relations = [
        'tags' => ['tags']
    ];

    public function setup()
    {
        if ( ! app(Auth::class)->user()->isAdmin())
        {
            $this->showOnlyPublishedTeams();
        }
    }

    public function showOnlyPublishedTeams()
    {
        return $this->whereHas('team', function ($team)
        {
           return $team->whereNotNull('published_at')
                       ->where('published_at', '<=', Carbon::now());
        });
    }

    public function teams($ids)
    {
        return $this->whereIn('team_id', $ids);
    }

    public function roles($ids)
    {
        return $this->whereIn('role_id', $ids);
    }

    public function groups($groups)
    {
        return $this->whereIn('group', $groups);
    }

    public function leader($leader)
    {
        return $leader == 'yes' ?
            $this->where('leader', true) :
            $this->where('leader', false);
    }

    /**
     * Find by search
     *
     * @param $search
     * @return mixed
     */
    public function search($search)
    {
        return $this->where(function($q) use ($search)
        {
            return $q->whereHas('reservations', function($r) use($search)
                 {
                     return $r->where('given_names', 'LIKE', "%$search%")
                              ->orWhere('surname', 'LIKE', "%$search%");
                 });
        });
    }

    /**
     * Sort by fields
     *
     * @param $sort
     * @return mixed
     */
    public function sort($sort)
    {
        $sortable = [
            'group', 'created_at', 'updated_at'
        ];

        $param = preg_split('/\|+/', $sort);
        $field = $param[0];
        $direction = isset($param[1]) ? $param[1] : 'asc';

        if ( in_array($field, $sortable) )
            return $this->orderBy($field, $direction);

        return $this;
    }
}