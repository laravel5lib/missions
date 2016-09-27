<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Transaction;
use League\Fractal\TransformerAbstract;

class TransactionTransformer extends TransformerAbstract {

    protected $availableIncludes = ['donor', 'fund'];

    /**
     * Transform the object into a basic array
     *
     * @param Transaction $transaction
     * @return array
     */
    public function transform(Transaction $transaction)
    {
        $array = [
            'id'          => $transaction->id,
            'amount'      => (int) $transaction->amount,
            'type'        => $transaction->type,
            'description' => $transaction->description,
            'payment'     => $transaction->payment,
            'created_at'  => $transaction->created_at->toDateTimeString(),
            'updated_at'  => $transaction->updated_at->toDateTimeString(),
            'links'       => [
                [
                    'rel' => 'self',
                    'uri' => '/funds/' . $transaction->id,
                ]
            ]
        ];

        return $array;
    }

    public function includeFund(Transaction $transaction)
    {
        $fund = $transaction->fund;

        return $this->item($fund, new FundraiserTransformer);
    }

    public function includeDonor(Transaction $transaction)
    {
        $donor = $transaction->donor;

        return $this->item($donor, new DonorTransformer);
    }
}