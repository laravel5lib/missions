<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Transaction;
use League\Fractal\TransformerAbstract;

class TransactionTransformer extends TransformerAbstract
{

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
            'amount'      => $transaction->amountInDollars(),
            'anonymous'   => (bool) $transaction->anonymous,
            'type'        => $transaction->type,
            'description' => $transaction->description,
            'details'     => $transaction->details,
            'created_at'  => $transaction->created_at->toRfc3339String(),
            'updated_at'  => $transaction->updated_at->toRfc3339String(),
            'links'       => [
                [
                    'rel' => 'self',
                    'uri' => '/transactions/' . $transaction->id,
                ]
            ]
        ];

        return $array;
    }

    /**
     * Include fund.
     *
     * @param Transaction $transaction
     * @return \League\Fractal\Resource\Item
     */
    public function includeFund(Transaction $transaction)
    {
        $fund = $transaction->fund;

        return $this->item($fund, new FundTransformer);
    }

    /**
     * Include donor.
     *
     * @param Transaction $transaction
     * @return \League\Fractal\Resource\Item
     */
    public function includeDonor(Transaction $transaction)
    {
        $donor = $transaction->donor;

        if (! $donor) {
            return null;
        }

        return $this->item($donor, new DonorTransformer);
    }
}
