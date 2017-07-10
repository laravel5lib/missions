<?php namespace App\Filters\v1;

use EloquentFilter\ModelFilter;

class CampaignTransportFilter extends ModelFilter
{
    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relatedModel => [method1, method2]]
     *
     * @var array
     */
    public $relations = [
        'passengers' => ['designation', 'groups']
    ];

    public $sortable = [
        'name', 'call_sign', 'vessel_no', 'capacity', 'type', 'created_at', 'updated_at', 'depart_at', 'arrive_at'
    ];

    public $searchable = [
        'name', 'call_sign', 'vessel_no', 'capacity'
    ];

    public function campaign($campaign)
    {
        return $this->where('campaign_id', $campaign);
    }

    /**
     * Filter by domestic or international
     */
    public function isDomestic($isDomestic)
    {
        return $isDomestic == 'yes' ?
            $this->where('domestic', true) :
            $this->where('domestic', false);
    }

    public function type($type)
    {
        return $this->where('type', $type);
    }

    public function maxPassengers($value)
    {
        return $this->has('passengers', '<=', $value);
    }

    public function minPassengers($value)
    {
        return $this->has('passengers', '>=', $value);
    }

    public function departAfter()
    {
        //
    }

    public function departBefore()
    {
        //
    }

    public function arriveAfter()
    {
        //
    }

    public function arriveBefore()
    {
        //
    }
}
