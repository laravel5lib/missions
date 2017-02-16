<?php namespace App\Filters\v1;

class TransportFilter extends Filter
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
        'name', 'call_sign', 'vessel_no', 'capacity', 'type',
        'departs_at', 'arrives_at', 'created_at', 'updated_at'
    ];

    /**
     * Fields that can be searched.
     *
     * @var array
     */
    public $searchable = [
        'name', 'call_sign', 'vessel_no', 'capacity'
    ];
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
}