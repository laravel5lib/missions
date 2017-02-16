<?php namespace App\Filters\v1;

class TripInterestFilter extends Filter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    /**
     * Fields that can be sorted.
     *
     * @var array
     */
    public $sortable = ['name', 'email', 'phone', 'created_at', 'updated_at', 'status'];

    /**
     * Fields that can be searched.
     *
     * @var array
     */
    public $searchable = ['name', 'email', 'phone', 'communication_preferences'];

    /**
     * Find by trip id.
     *
     * @param $id
     * @return mixed
     */
    public function trip($id)
    {
        if(! $id) return $this;

        return $this->where('trip_id', $id);
    }

    /**
     * Filter by status.
     *
     * @param $status
     * @return mixed
     */
    public function status($status)
    {
        if(! $status) return $this;

        return $this->where('status', $status);
    }

    /**
     * Filter by trip type.
     *
     * @param $type
     * @return mixed
     */
    public function tripType($type)
    {
        if(! $type) return $this;

        return $this->whereHas('trip', function($trip) use($type) {
            $trip->where('type', $type);
        });
    }

    /**
     * Find by group id.
     *
     * @param $id
     * @return mixed
     */
    public function group($id)
    {
        if(! $id) return $this;

        return $this->whereHas('trip', function($trip) use($id) {
            return $trip->where('group_id', $id);
        });
    }

    /**
     * Find by campaign id.
     *
     * @param $id
     * @return mixed
     */
    public function campaign($id)
    {
        if(! $id) return $this;

        return $this->whereHas('trip', function($trip) use($id) {
            return $trip->where('campaign_id', $id);
        });
    }
}
