<?php namespace App\Filters\v1;

use EloquentFilter\ModelFilter;

class UserFilter extends ModelFilter
{

    // https://laravel.com/docs/5.2/queries#json-where-clauses

    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [method1, method2]]
    *
    * @var array
    */
    public $relations = [
        'tags' => ['tags']
    ];

    /**
     * Find by public or private
     *
     * @param $isPublic
     * @return mixed
     */
    public function isPublic($isPublic)
    {
        return $isPublic == 'yes' ?
            $this->where('public', true) :
            $this->where('public', false);
    }

    public function gender($gender)
    {
        return $this->where('gender', $gender);
    }

    public function status($status)
    {
        return $this->where('status', $status);
    }

    public function country($countries)
    {
        return $this->whereIn('country_code', $countries);
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
                ->orWhere('email', 'LIKE', "%$search%")
                ->orWhere('alt_email', 'LIKE', "%$search%")
                ->orWhere('city', 'LIKE', "%$search%")
                ->orWhere('state', 'LIKE', "%$search%")
                ->orWhere('phone_one', 'LIKE', "$search%")
                ->orWhere('phone_two', 'LIKE', "$search%")
                ->orWhere('zip', 'LIKE', "$search%")
                ->orWhere('url', 'LIKE', "%$search%");
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
            'name', 'email', 'alt_email', 'zip',
            'country_code', 'state', 'city', 'created_at',
            'updated_at', 'birthday'
        ];

        $param = preg_split('/\|+/', $sort);
        $field = $param[0];
        $direction = isset($param[1]) ? $param[1] : 'asc';

        if ( in_array($field, $sortable) )
            return $this->orderBy($field, $direction);

        return $this;
    }
}