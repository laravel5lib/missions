<?php namespace App\Filters\v1;

class DueFilter extends Filter
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
    public $sortable = ['due_at', 'grace_period'];

    /**
     * Default searchable fields.
     *
     * @var array
     */
    public $searchable = ['payment.cost.name'];

    /**
     * By payment status
     * @param  string $status
     * @return mixed
     */
    public function status($status)
    {
        switch ($status) {
            case 'overdue':
                return $this->overdue();
                break;
            case 'late':
                return $this->late();
                break;
            case 'paid':
                return $this->paid();
                break;
            case 'pending':
                return $this->pending();
                break;
            case 'extended':
                return $this->extended();
                break;
            default:
                return $this;
                break;
        }
    }
}
