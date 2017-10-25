<?php namespace App\Filters\v1;

class GroupFilter extends Filter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [method1, method2]]
    *
    * @var array
    */
    public $relations = [];

    /**
     * Fields that can be sorted.
     *
     * @var array
     */
    public $sortable = [
        'name', 'type', 'email', 'zip',
        'country_code', 'state', 'city', 'created_at',
        'updated_at'
    ];

    /**
     * Fields that can be searched.
     *
     * @var array
     */
    public $searchable = [
        'name', 'type', 'email', 'city', 'state',
        'phone_one', 'phone_two', 'zip', 'slug.url'
    ];

    /**
     * Automatically filter by approved unless
     * pending parameter is present.
     */
    public function setup()
    {
        if (! $this->input('pending')) {
            $this->approved();
        }
    }

    /**
     * Extended method NOT WORKING
     */
    public function search($terms)
    {
        return $this->where('name', 'LIKE', "%$terms%");
    }


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

    /**
     * Find by type
     *
     * @param $type
     * @return mixed
     */
    public function type($type)
    {
        return $this->where('type', $type);
    }

    /**
     * Find by country
     *
     * @param $country
     * @return mixed
     */
    public function country($country)
    {
        return $this->where('country_code', $country);
    }

    /**
     * Find by state
     *
     * @param $state
     * @return mixed
     */
    public function state($state)
    {
        return $this->where('state', $state);
    }

    /**
     * Find only public groups
     */
    public function onlyPublicGroups()
    {
        $this->where('public', true);
    }

    /**
     * Find by url slug.
     *
     * @param $url
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function url($url)
    {
        return $this->where('url', $url);
    }

    /**
     * Find approved groups.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function approved()
    {
        return $this->orWhere('status', 'approved');
    }

    /**
     * Find pending groups.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function pending()
    {
        return $this->orWhere('status', 'pending');
    }
}
