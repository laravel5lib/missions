<?php namespace App\Filters\v1;

class DonorFilter extends Filter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    /**
     * Default sortable fields.
     *
     * @var array
     */
    public $sortable = ['name'];

    /**
     * Default searchable fields.
     *
     * @var array
     */
    public $searchable = ['name'];

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
     * Filter by designated group.
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function group($id)
    {
        return $this->byDesignation('groups', $id);
    }

    /**
     * Filter by designated campaign.
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function campaign($id)
    {
        return $this->byDesignation('campaigns', $id);
    }

    /**
     * Filter by designated reservation.
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function cause($id)
    {
        return $this->byDesignation('causes', $id);
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
        return $this->whereHas('funds', function($donation) use($id, $type) {
            $donation->where('fundable_type', $type)
                ->where('fundable_id', $id);
        });
    }

    /**
     * Filter by user.
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function userAccount($id)
    {
        return $this->byAccountHolder('users', $id);
    }

    /**
     * Filter by group.
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function groupAccount($id)
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
        return $this->where('account_id', $id)
            ->where('account_type', $type);
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
