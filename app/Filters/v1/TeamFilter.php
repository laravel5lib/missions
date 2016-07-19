<?php namespace App\Filters\v1;

use Carbon\Carbon;
use Dingo\Api\Auth\Auth;
use EloquentFilter\ModelFilter;

class TeamFilter extends ModelFilter
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
            $this->showOnlyPublished();
        }
    }

    public function showOnlyPublished()
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

    public function members($members)
    {
        if(count($members) < 2) return $this;

        return $this->has('members', '>=', $members[0])
                    ->has('members', '<=', $members[1]);
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
            return $q->where('call_sign', 'LIKE', "%$search%")
                     ->orWhereHas('region', function($r) use($search)
                     {
                         return $r->where('name', 'LIKE', "%$search%");
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
            'call_sign', 'published_at', 'created_at', 'updated_at'
        ];

        $param = preg_split('/\|+/', $sort);
        $field = $param[0];
        $direction = isset($param[1]) ? $param[1] : 'asc';

        if ( in_array($field, $sortable) )
            return $this->orderBy($field, $direction);

        return $this;
    }
}