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
            'region_id'    => $accommodation->region_id,
            'name'         => $accommodation->name,
            'rooms_count'  => $accommodation->roomsCount()->all(),
            'room_types'   => $this->getAvailableRooms($accommodation),
            'occupants_count' => $accommodation->occupantsCount()->total(),
            'address_one'  => $accommodation->address_one,
            'address_two'  => $accommodation->address_two,
            'city'         => $accommodation->city,
            'state'        => $accommodation->state,
            'zip'          => $accommodation->zip,
            'phone'        => $accommodation->phone,
            'fax'          => $accommodation->fax,
            'country'      => [
                                'code' => $accommodation->country_code,
                                'name' => country($accommodation->country_code)
                              ],
            'email'        => $accommodation->email,
            'url'          => $accommodation->url,
            'short_desc'   => $accommodation->short_desc,
            'created_at'   => $accommodation->created_at->toDateTimeString(),
            'updated_at'   => $accommodation->updated_at->toDateTimeString(),
            'deleted_at'   => $accommodation->deleted_at ? $accommodation->deleted_at->toDateTimeString() : null,
            'links'        => [
                [
                    'rel' => 'self',
                    'uri' => '/api/regions/'.$accommodation->region_id.'/accommodations/' . $accommodation->id
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

    private function getAvailableRooms(Accommodation $accommodation)
    {
        return $accommodation->availableRoomTypes->keyBy('name')->map(function($type) {
            return $type->pivot->available_rooms;
        })->put('total', $accommodation->availableRoomTypes->count())->all();
    }
}