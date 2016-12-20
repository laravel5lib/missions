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
        'details->last_four',
        'details->brand', 'details->cardholder', 'details->number',
        'details->charge_id', 'fund.name', 'donor.name',
        'fund.class', 'fund.item', 'donor.phone', 'donor.email'
    ];

    public $sortable = [
        'type', 'amount', 'created_at'
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
     * By payment method.
     *
     * @param $method
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function payment($method)
    {
        return $this->where('details->type', $method);
    }

    /**
     * From minimum amount.
     *
     * @param $amount
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function minAmount($amount)
    {
        if (! $amount) return null;

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
        if (! $amount) return null;

        return $this->where('amount', '<=', $amount);
    }

    /**
     * To minimum date.
     *
     * @param $date
     * @return \Illuminate\Database\Eloquent\Builder|null
     */
    public function minDate($date)
    {
        if (! $date) return null;

        return $this->whereDate('created_at', '>=', $date);
    }

    /**
     * To maximum date.
     *
     * @param $date
     * @return \Illuminate\Database\Eloquent\Builder|null
     */
    public function maxDate($date)
    {
        if (! $date) return null;

        return $this->whereDate('created_at', '<=', $date);
    }
}
