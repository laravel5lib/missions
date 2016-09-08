<?php namespace App\Filters\v1;

use Carbon\Carbon;
use EloquentFilter\ModelFilter;

class FundraiserFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public function active()
    {
        return $this->where('ended_at', '>=', Carbon::now());
    }

    public function url($slug)
    {
        return $this->where('fundraisers.url', $slug);
    }

    public function type($type)
    {
        return $this->where('sponsor_type', str_plural($type));
    }

    public function sponsor($url)
    {
        if (starts_with($url, 'groups/')) {
            $url = str_replace('groups/', '', $url);

            return $this->where('sponsor_type', 'groups')->join('groups', function ($join) use ($url) {
                $join->on('groups.id', '=', 'fundraisers.sponsor_id')
                    ->where('groups.url', '=', $url);
            })->select('fundraisers.*');
        }

        if (starts_with($url, '@')) {
            $url = str_replace('@', '', $url);

            return $this->where('sponsor_type', 'users')->join('users', function ($join) use ($url) {
                $join->on('users.id', '=', 'fundraisers.sponsor_id')
                    ->where('users.url', '=', $url);
            })->select('fundraisers.*');
        }

        return $this;
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
            return $q->where('name', 'LIKE', "%$search%");
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
            'new', 'popular'
        ];

        $param = preg_split('/\|+/', $sort);
        $field = $param[0];
        $direction = isset($param[1]) ? $param[1] : 'asc';

        if ( in_array($field, $sortable) ) {

            switch ($field) {
                case 'new':
                    return $this->latest();
                    break;
                case 'popular':
                    return $this; // doesn't work yet :(
                    break;
            }
        }

        return $this;
    }
}
