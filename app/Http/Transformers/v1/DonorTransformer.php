<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Donor;
use League\Fractal\TransformerAbstract;

class DonorTransformer extends TransformerAbstract
{
    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'donations'
    ];

    /**
     * @var array
     */
    private $designation = [];

    /**
     * DonorTransformer constructor.
     * @param array $designation
     * @internal param $id
     */
    public function __construct(array $designation = [])
    {
        $this->designation = $designation;
    }


    /**
     * Turn this item object into a generic array
     *
     * @param Donor $donor
     * @return array
     */
    public function transform(Donor $donor)
    {
        $array = [
            'id'                  => $donor->id,
            'name'                => $donor->name,
            'email'               => $donor->email,
            'phone'               => $donor->phone,
            'address_one'         => $donor->address_one,
            'address_two'         => $donor->address_two,
            'city'                => $donor->city,
            'state'               => $donor->state,
            'zip'                 => $donor->zip,
            'country_code'        => $donor->country_code,
            'country_name'        => country($donor->country_code),
            'account_holder_id'   => $donor->account_holder_id,
            'account_holder_type' => $donor->account_holder_type,
            'total_donated'       => (int) $donor->totalDonated($this->designation),
            'created_at'          => $donor->created_at->toDateTimeString(),
            'updated_at'          => $donor->updated_at->toDateTimeString(),
            'links'               => [
                [
                    'rel' => 'self',
                    'uri' => '/donors/' . $donor->id,
                ]
            ]
        ];

        return $array;
    }

    /**
     * Include Donations
     *
     * @param Donor $donor
     * @return \League\Fractal\Resource\Item
     */
    public function includeDonations(Donor $donor)
    {
        $donations = $donor->donations()->filter($this->designation)->get();

        return $this->collection($donations, new DonationTransformer);
    }
}