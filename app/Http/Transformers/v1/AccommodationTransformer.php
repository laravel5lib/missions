<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Accommodation;
use League\Fractal\TransformerAbstract;

class AccommodationTransformer extends TransformerAbstract {

    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'region'
    ];

    /**
     * Transform the object into a basic array
     *
     * @param Accommodation $accommodation
     * @return array
     */
    public function transform(Accommodation $accommodation)
    {
        return [
            'id'           => $accommodation->id,
            'name'         => $accommodation->name,
            'address_one'  => $accommodation->address_one,
            'address_two'  => $accommodation->address_two,
            'city'         => $accommodation->city,
            'state'        => $accommodation->state,
            'zip'          => $accommodation->zip,
            'phone'        => $accommodation->phone,
            'fax'          => $accommodation->fax,
            'country_code' => $accommodation->country_code,
            'country_name' => country($accommodation->country_code),
            'email'        => $accommodation->email,
            'url'          => $accommodation->url,
            'region_id'    => $accommodation->region_id,
            'short_desc'   => $accommodation->short_desc,
            'check_in_at'  => $accommodation->check_in_at ? $accommodation->check_in_at->toDateTimeString() : null,
            'check_out_at' => $accommodation->check_out_at ? $accommodation->check_out_at->toDateTimeString() : null,
            'created_at'   => $accommodation->created_at->toDateTimeString(),
            'updated_at'   => $accommodation->updated_at->toDateTimeString(),
            'links'        => [
                [
                    'rel' => 'self',
                    'uri' => '/accommodations/' . $accommodation->id
                ]
            ]
        ];
    }

    /**
     * Include Region
     *
     * @param Accommodation $accommodation
     * @return \League\Fractal\Resource\Item
     */
    public function includeRegion(Accommodation $accommodation)
    {
        $region = $accommodation->region;

        return $this->item($region, new RegionTransformer);
    }
}