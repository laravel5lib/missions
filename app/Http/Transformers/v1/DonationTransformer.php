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
            'name'         => $donation->name,
            'company_name' => $donation->company_name,
            'amount'       => $donation->amount / 100,
            'description'  => $donation->description,
            'message'      => $donation->message,
            'anonymous'    => (bool) $donation->anonymous,
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