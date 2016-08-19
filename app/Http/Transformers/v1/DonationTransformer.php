<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Donation;
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
     * @param Donation $donation
     * @return array
     */
    public function transform(Donation $donation)
    {
        $array = [
            'id'           => $donation->id,
            'anonymous'    => (bool) $donation->anonymous,
            'amount'       => (int) $donation->amount,
            'currency'     => $donation->currency,
            'payment_type' => $donation->payment_type,
            'description'  => $donation->description,
            'message'      => $donation->message,
            'created_at'   => $donation->created_at->toDateTimeString(),
            'updated_at'   => $donation->updated_at->toDateTimeString(),
            'links'        => [
                [
                    'rel' => 'self',
                    'uri' => '/donations/' . $donation->id,
                ]
            ]
        ];

        return $array;
    }

    /**
     * Include Donor
     *
     * @param Donation $donation
     * @return \League\Fractal\Resource\Item
     */
    public function includeDonor(Donation $donation)
    {
        //
    }
}