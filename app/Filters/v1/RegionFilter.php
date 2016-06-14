<?php namespace App\Filters\v1;

use EloquentFilter\ModelFilter;

class RegionFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [method1, method2]]
    *
    * @var array
    */
    public $relations = [];

    /**
     * Filter by country code
     *
     * @param $codes
     * @return mixed
     */
    public function countries($codes)
    {
        return $this->whereIn('country_code', $codes);
    }

    public function teams($teams)
    {
        if(count($teams) < 2) return $this;

        return $this->has('teams', '>=', $teams[0])
                    ->has('teams', '<=', $teams[1]);
    }

    public function hasAccommodations($hasAccommodations)
    {
        return $hasAccommodations == 'yes' ?
            $this->has('accommodations') :
            $this->has('accommodations', '<', 1);
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
            return $q->where('name', 'LIKE', "%$search%")
                ->orWhere('call_sign', 'LIKE', "%$search%")
                ->orWhereHas('accommodations', function($a) use($search)
                {
                    return $a->where('name', 'LIKE', "%$search%");
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
            'name', 'country_code', 'call_sign', 'created_at', 'updated_at'
        ];

        $param = preg_split('/\|+/', $sort);
        $field = $param[0];
        $direction = isset($param[1]) ? $param[1] : 'asc';

        if ( in_array($field, $sortable) )
            return $this->orderBy($field, $direction);

        return $this;
    }
}