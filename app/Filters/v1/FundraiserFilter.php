<?php namespace App\Filters\v1;

use Carbon\Carbon;

class FundraiserFilter extends Filter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    /**
     * Fields that are sortable.
     *
     * @var array
     */
    public $sortable = ['created_at'];

    /**
     * Fields that are searchable.
     *
     * @var array
     */
    public $searchable = ['name'];

    /**
     * Find public fundraisers.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function isPublic()
    {
        return $this->public();
    }

    /**
     * Get active fundraisers.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function active()
    {
        return $this->where('ended_at', '>=', Carbon::now())
                    ->where('started_at', '<=', Carbon::now());
    }

    /**
     * Get archived fundraisers.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function archived()
    {
        return $this->where('ended_at', '<', Carbon::now());
    }

    /**
     * Find by url.
     *
     * @param $slug
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function url($slug)
    {
        return $this->where('fundraisers.url', $slug);
    }

    /**
     * Find by fundraiser type.
     *
     * @param $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function type($type)
    {
        return $this->where('type', $type);
    }

    /**
     * Find by sponsor type.
     *
     * @param $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function sponsorType($type)
    {
        return $this->where('sponsor_type', str_plural($type));
    }

    public function sponsorId($id)
    {
        return $this->where('sponsor_id', $id);
    }

    /**
     * Find by sponsor url.
     *
     * @param $url
     * @return $this
     */
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
