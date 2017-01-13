<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Due;
use League\Fractal\TransformerAbstract;

class DueTransformer extends TransformerAbstract
{
    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = [];

    /**
     * Turn this item object into a generic array
     *
     * @param Due $due
     * @return array
     */
    public function transform(Due $due)
    {
        $due->load('payment.cost');

        return [
            'id'           => $due->id,
            'cost'         => $due->payment->cost->name,
            'amount'       => (int) $due->payment->amount_owed,
            'percent'      => (int) $due->payment->percent_owed,
            'balance'      => (int) $due->outstanding_balance,
            'due_at'       => $due->due_at->toDateTimeString(),
            'grace_period' => $due->grace_period,
            'status'       => $due->getStatus()
        ];
    }

}