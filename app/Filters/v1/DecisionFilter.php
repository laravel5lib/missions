<?php namespace App\Filters\v1;

use EloquentFilter\ModelFilter;

class DecisionFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [method1, method2]]
    *
    * @var array
    */
    public $relations = [];

    /**
     * Find by gender
     *
     * @param $gender
     * @return mixed
     */
    public function gender($gender)
    {
        return $this->where('gender', $gender);
    }

    /**
     * Find by age group
     *
     * @param $age
     * @return mixed
     */
    public function age($age)
    {
        return $this->where('age_group', $age);
    }

    /**
     * Find by decision
     *
     * @param $decision
     * @return mixed
     */
    public function decision($decision)
    {
        return $decision == 'yes' ?
            $this->where('decision', true) :
            $this->where('decision', false);
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
                ->orWhere('email', 'LIKE', "$search%")
                ->orWhere('phone', 'LIKE', "$search%")
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
            'name', 'gender', 'age_group', 'phone',
            'email', 'created_at', 'updated_at'
        ];

        $param = preg_split('/\|+/', $sort);
        $field = $param[0];
        $direction = isset($param[1]) ? $param[1] : 'asc';

        if ( in_array($field, $sortable) )
            return $this->orderBy($field, $direction);

        return $this;
    }
}