<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Due;
use League\Fractal\ParamBag;
use League\Fractal\TransformerAbstract;

class DueTransformer extends TransformerAbstract
{
    private $validParams = ['status'];

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
            'cost'         => $due->payment->cost->name,
            'amount'       => (int) $due->payment->amount_owed,
            'percent'      => (int) $due->payment->percent_owed,
            'balance'      => (int) $due->outstanding_balance,
            'due_at'       => $due->due_at->toDateTimeString(),
            'grace_period' => $due->grace_period,
            'status'       => $due->getStatus()
        ];
    }

    private function validateParams($params)
    {
        $usedParams = array_keys(iterator_to_array($params));
        if ($invalidParams = array_diff($usedParams, $this->validParams)) {
            throw new \Exception(sprintf(
                'Invalid param(s): "%s". Valid param(s): "%s"',
                implode(',', $usedParams),
                implode(',', $this->validParams)
            ));
        }
    }

}