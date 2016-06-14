<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Payment;
use League\Fractal\TransformerAbstract;

class PaymentTransformer extends TransformerAbstract
{
    /**
     * Turn this item object into a generic array
     *
     * @param Payment $payment
     * @return array
     */
    public function transform(Payment $payment)
    {
        return [
            'id'           => $payment->id,
            'amount_owed'  => (int) $payment->amount_owed,
            'percent_owed' => (int) $payment->percent_owed,
            'due_at'       => $payment->due_at ? $payment->due_at->toDateTimeString() : null,
            'grace_period' => (int) $payment->grace_period,
            'upfront'      => (bool) $payment->upfront
        ];
    }

}