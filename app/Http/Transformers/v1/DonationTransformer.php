<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Transaction;
use League\Fractal\TransformerAbstract;

class DonationTransformer extends TransformerAbstract
{
    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'donor'
    ];

    /**
     * Turn this item object into a generic array
     *
     * @param Transaction $donation
     * @return array
     */
    public function transform(Transaction $donation)
    {
        $donation->load('donor');

        $array = [
            'id'          => $donation->id,
            'name'        => $donation->donor->name,
            'amount'      => $donation->amountInDollars(),
            'anonymous'   => (bool) $donation->anonymous,
            'type'        => $donation->type,
            'description' => $donation->description,
            'details'     => $donation->details,
            'created_at'  => $donation->created_at->toDateTimeString(),
            'updated_at'  => $donation->updated_at->toDateTimeString(),
            'links'       => [
                [
                    'rel' => 'self',
                    'uri' => '/transactions/' . $donation->id,
                ]
            ]
        ];

        return $array;
    }

    /**
     * Include Donor
     *
     * @param Transaction $donation
     * @return \League\Fractal\Resource\Item
     */
    public function includeDonor(Transaction $donation)
    {
        $donor = $donation->donor;

        return $this->item($donor, new DonorTransformer);
    }
}
