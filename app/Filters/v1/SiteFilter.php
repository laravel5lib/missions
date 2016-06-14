<?php namespace App\Filters\v1;

use EloquentFilter\ModelFilter;

class SiteFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [method1, method2]]
    *
    * @var array
    */
    public $relations = [];

    /**
     * Find by types
     *
     * @param $types
     * @return mixed
     */
    public function types($types)
    {
        return $this->whereIn('site_type', $types);
    }

    /**
     * Find by total reached range
     *
     * @param $reached
     * @return $this
     */
    public function reached($reached)
    {
        if(count($reached) < 2) return $this;

        return $this->whereBetween('total_reached', $reached);
    }

    /**
     * Find by total decisions range
     *
     * @param $decisions
     * @return $this
     */
    public function decisions($decisions)
    {
        if(count($decisions) < 2) return $this;

        return $this->whereBetween('total_decisions', $decisions);
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
                ->orWhere('lat', 'LIKE', "$search%")
                ->orWhere('long', 'LIKE', "$search%")
                ->orWhereHas('region', function($r) use($search)
                {
                    return $r->where('name', 'LIKE', "%$search%");
                })
                ->orWhereHas('reservation', function($r) use($search)
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
            'call_sign', 'site_type', 'total_reached',
            'total_decisions', 'created_at', 'updated_at'
        ];

        $param = preg_split('/\|+/', $sort);
        $field = $param[0];
        $direction = isset($param[1]) ? $param[1] : 'asc';

        if ( in_array($field, $sortable) )
            return $this->orderBy($field, $direction);

        return $this;
    }
}