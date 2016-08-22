<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Visa;
use League\Fractal\TransformerAbstract;

class VisaTransformer extends TransformerAbstract
{
    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'user', 'reservations'
    ];

    /**
     * Transform the object into a basic array
     *
     * @param Visa $visa
     * @return array
     */
    public function transform(Visa $visa)
    {
        $array = [
            'id' => $visa->id,
            'given_names' => $visa->given_names,
            'surname' => $visa->surname,
            'number' => $visa->number,
            'country_code' => $visa->country_code,
            'country_name' => country($visa->country_code),
            'upload_id' => $visa->upload_id,
            'issued_at' => $visa->issued_at->format('Y-m-d'),
            'expires_at' => $visa->expires_at->format('Y-m-d'),
            'created_at' => $visa->created_at->toDateTimeString(),
            'updated_at' => $visa->updated_at->toDateTimeString(),
            'links'        => [
                [
                    'rel' => 'self',
                    'uri' => '/visas/' . $visa->id,
                ]
            ]
        ];

        return $array;
    }

    /**
     * Include User
     *
     * @param Visa $visa
     * @return \League\Fractal\Resource\Item
     */
    public function includeUser(Visa $visa)
    {
        $user = $visa->user;

        return $this->item($user, new UserTransformer);
    }

    /**
     * Include Reservations
     *
     * @param Visa $visa
     * @return \League\Fractal\Resource\Item
     */
    public function includeReservations(Visa $visa)
    {
        $reservations = $visa->reservations;

        return $this->collection($reservations, new ReservationTransformer);
    }
}