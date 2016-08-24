<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Donor;
use App\Models\v1\Group;
use App\Models\v1\User;
use League\Fractal\TransformerAbstract;

class DonorTransformer extends TransformerAbstract
{
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
            'zip'                 => $donor->zip,
            'country_code'        => $donor->country_code,
            'country_name'        => country($donor->country_code),
            'total_donated'       => (int) $donor->donations($this->designation)->sum('amount'),
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
}