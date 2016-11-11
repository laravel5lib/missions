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
    public $sortable = ['name', 'email', 'phone', 'created_at', 'updated_at'];

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
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function trip($id)
    {
        return $this->where('trip_id', $id);
    }

    /**
     * Filter by trip type.
     *
     * @param $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function tripType($type)
    {
        return $this->whereHas('trip', function($trip) use($type) {
            $trip->where('type', $type);
        });
    }

    /**
     * Find by group id.
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function group($id)
    {
        return $this->whereHas('trip', function($trip) use($id) {
            return $trip->where('group_id', $id);
        });
    }

    /**
     * Find by campaign id.
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function campaign($id)
    {
        return $this->whereHas('trip', function($trip) use($id) {
            return $trip->where('campaign_id', $id);
        });
    }
}
