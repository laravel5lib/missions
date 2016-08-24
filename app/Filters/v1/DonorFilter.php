<?php namespace App\Filters\v1;

use EloquentFilter\ModelFilter;

class DonorFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    /**
     * Filter by designated reservation.
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function reservation($id)
    {
        return $this->byDesignation('reservations', $id);
    }

    /**
     * Filter by designated trip.
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function trip($id)
    {
        return $this->byDesignation('trips', $id);
    }

    /**
     * Filter by designated project.
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function project($id)
    {
        return $this->byDesignation('projects', $id);
    }

    /**
     * Filter by designation.
     *
     * @param $type
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function byDesignation($type, $id)
    {
        return $this->whereHas('donations', function($donation) use($id, $type) {
            $donation->where('designation_type', $type)
                ->where('designation_id', $id);
        });
    }

    /**
     * Filter by user.
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function user($id)
    {
        return $this->byAccountHolder('users', $id);
    }

    /**
     * Filter by group.
     *
     * @param $id
     * @return ModelFilter
     */
    public function group($id)
    {
        return $this->byAccountHolder('groups', $id);
    }

    /**
     * Filter by account holder.
     *
     * @param $type
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function byAccountHolder($type, $id)
    {
        return $this->where('account_holder_id', $id)
            ->where('account_holder_type', $type);
    }

    /**
     * Filter by start date.
     *
     * @param $started_at
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function starts($started_at)
    {
        return $this->whereHas('donations', function($donation) use($started_at) {
            $donation->whereDate('created_at', '>=', $started_at);
        });
    }

    /**
     * Filter by end date.
     * 
     * @param $ended_at
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function ends($ended_at)
    {
        return $this->whereHas('donations', function($donation) use($ended_at) {
            $donation->whereDate('created_at', '<=', $ended_at);
        });
    }

}
