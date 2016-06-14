<?php namespace App\Filters\v1;

use EloquentFilter\ModelFilter;

class TransportFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [method1, method2]]
    *
    * @var array
    */
    public $relations = [];

    /**
     * Filter by campaign
     *
     * @param $campaign
     * @return mixed
     */
    public function campaign($campaign)
    {
        return $this->where('campaign_id', $campaign);
    }

    /**
     * Filter by domestic or international
     *
     * @param $isDomestic
     * @return mixed
     */
    public function isDomestic($isDomestic)
    {
        return $isDomestic == 'yes' ?
            $this->where('domestic', true) :
            $this->where('domestic', false);
    }

    /**
     * Filter by type
     *
     * @param $type
     * @return mixed
     */
    public function type($type)
    {
        return $this->where('type', $type);
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
            return $q->where('name', 'LIKE', "%$search%")
                ->orWhere('call_sign', 'LIKE', "%$search%")
                ->orWhere('vessel_no', 'LIKE', "%$search%")
                ->orWhere('capacity', 'LIKE', "%$search%");
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
            'name', 'call_sign', 'vessel_no', 'capacity', 'type',
            'departs_at', 'arrives_at', 'created_at', 'updated_at'
        ];

        $param = preg_split('/\|+/', $sort);
        $field = $param[0];
        $direction = isset($param[1]) ? $param[1] : 'asc';

        if ( in_array($field, $sortable) )
            return $this->orderBy($field, $direction);

        return $this;
    }
}