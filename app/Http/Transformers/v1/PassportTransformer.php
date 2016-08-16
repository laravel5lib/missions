<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Passport;
use League\Fractal\TransformerAbstract;

class PassportTransformer extends TransformerAbstract
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
     * @param Passport $passport
     * @return array
     */
    public function transform(Passport $passport)
    {
        $array = [
            'id' => $passport->id,
            'given_names' => $passport->given_names,
            'surname' => $passport->surname,
            'number' => $passport->number,
            'birth_country' => $passport->birth_country,
            'birth_country_name' => country($passport->birth_country),
            'citizenship' => $passport->citizenship,
            'citizenship_name' => country($passport->citizenship),
            'upload_id' => $passport->upload_id,
            'issued_at' => $passport->issued_at->format('Y-m-d'),
            'expires_at' => $passport->expires_at->format('Y-m-d'),
            'created_at' => $passport->created_at->toDateTimeString(),
            'updated_at' => $passport->updated_at->toDateTimeString(),
            'links'        => [
                [
                    'rel' => 'self',
                    'uri' => '/passports/' . $passport->id,
                ]
            ]
        ];

        return $array;
    }

    /**
     * Include User
     *
     * @param Passport $passport
     * @return \League\Fractal\Resource\Item
     */
    public function includeUser(Passport $passport)
    {
        $user = $passport->user;

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