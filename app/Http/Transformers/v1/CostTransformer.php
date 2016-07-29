<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Cost;
use League\Fractal\TransformerAbstract;

class CostTransformer extends TransformerAbstract
{
    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'reservations', 'payments'
    ];

    /**
     * Transform the object into a basic array
     *
     * @param Cost $cost
     * @return array
     */
    public function transform(Cost $cost)
    {
        $array = [
            'id'          => $cost->id,
            'name'        => $cost->name,
            'description' => $cost->description,
            'amount'      => $cost->amount,
            'active_at'   => $cost->active_at->toDateTimeString(),
            'type'        => $cost->type
        ];

        if ($cost->pivot)
        {
            $array = [
                'cost_id'      => $cost->id,
                'name'         => $cost->name,
                'description'  => $cost->description,
                'amount'       => $cost->amount,
                'active_at'    => $cost->active_at->toDateTimeString(),
                'grace_period' => (int) $cost->pivot->grace_period,
                'type'         => $cost->type,
                'updated_at'   => $cost->pivot->updated_at->toDateTimeString()
            ];
        }

        $array['links'] = [
            [
                'rel' => 'self',
                'uri' => '/costs/' . $cost->id,
            ]
        ];

        return $array;
    }

    /**
     * Include Reservations
     *
     * @param Cost $cost
     * @return \League\Fractal\Resource\Item
     */
    public function includeReservations(Cost $cost)
    {
        $reservations = $cost->reservations;

        return $this->collection($reservations, new ReservationTransformer);
    }

    /**
     * Include Payments
     *
     * @param Cost $cost
     * @return \League\Fractal\Resource\Collection
     */
    public function includePayments(Cost $cost)
    {
        $payments = $cost->payments;

        return $this->collection($payments, new PaymentTransformer);
    }
}