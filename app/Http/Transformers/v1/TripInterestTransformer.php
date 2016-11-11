<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\TripInterest;
use League\Fractal\TransformerAbstract;

class TripInterestTransformer extends TransformerAbstract
{

    /**
     * List of resources to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'trip'
    ];

    /**
     * Turn this item object into a generic array
     *
     * @param TripInterest $interest
     * @return array
     */
    public function transform(TripInterest $interest)
    {
        return [
            'id'                        => $interest->id,
            'trip_id'                   => $interest->trip_id,
            'status'                    => $interest->status,
            'name'                      => $interest->name,
            'email'                     => $interest->email,
            'phone'                     => $interest->phone,
            'communication_preferences' => $interest->communication_preferences,
            'created_at'                => $interest->created_at->toDateTimeString(),
            'updated_at'                => $interest->updated_at->toDateTimeString(),
            'links'                     => [
                [
                    'rel' => 'self',
                    'uri' => '/trips/' . $interest->trip_id . '/interests/' . $interest->id,
                ]
            ],
        ];
    }

    /**
     * Include TripInterest
     *
     * @param TripInterest $interest
     * @return \League\Fractal\Resource\Item
     */
    public function includeTrip(TripInterest $interest)
    {
        $trip = $interest->trip;

        return $this->item($trip, new TripTransformer);
    }

}