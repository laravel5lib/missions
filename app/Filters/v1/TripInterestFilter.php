<?php namespace App\Filters\v1;

use EloquentFilter\ModelFilter;

class TripInterestFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

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

    /**
     * Find my communication preference type.
     *
     * @param $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function preference($type)
    {
        return $this->where('communication_preferences', 'LIKE', "%$type%");
    }
}
