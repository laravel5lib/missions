<?php namespace App\Filters\v1;

use Carbon\Carbon;
use App\Models\v1\Slug;

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

    public function search($value)
    {
        return $this->where('name', 'LIKE', "%$value%");
    }

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
        return $this->whereHas('slug', function($q) use($slug) {
            return $q->where('url', $slug);
        });
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

    public function fund($id)
    {
        return $this->where('fund_id', $id);
    }

    /**
     * Find by sponsor url.
     *
     * @param $url
     * @return $this
     */
    public function sponsor($url)
    {
        $slug = Slug::where('url', $url)->first();

        return $this->where('sponsor_id', $slug->slugable_id);
    }
}
