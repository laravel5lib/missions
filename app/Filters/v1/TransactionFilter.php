<?php namespace App\Filters\v1;

class TransactionFilter extends Filter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    /**
     * Fields that can be searched.
     *
     * @var array
     */
    public $searchable = [
        'description', 'payment->zip', 'payment->last_four',
        'payment->brand', 'payment->cardholder', 'payment->number',
        'payment->charge_id', 'fund.name', 'donor.name',
        'fund.class', 'fund.item'
    ];

    public $sortable = [
        'description', 'type', 'amount',
        'fund.class', 'fund.item', 'fund.name'
    ];

    /**
     * By fund.
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function fund($id)
    {
        return $this->where('fund_id', $id);
    }

    /**
     * By Donor.
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function donor($id)
    {
        return $this->where('donor_id', $id);
    }

    /**
     * By Type.
     *
     * @param $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function type($type)
    {
        return $this->where('type', $type);
    }

    /**
     * From minimum amount.
     *
     * @param $amount
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function minAmount($amount)
    {
        return $this->where('amount', '>=', $amount);
    }

    /**
     * To maximum amount.
     *
     * @param $amount
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function maxAmount($amount)
    {
        return $this->where('amount', '<=', $amount);
    }
}
