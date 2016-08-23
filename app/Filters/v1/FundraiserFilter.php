<?php namespace App\Filters\v1;

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

    public function url($slug)
    {
        return $this->where('fundraisers.url', $slug);
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
}
