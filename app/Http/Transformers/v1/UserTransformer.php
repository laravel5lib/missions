<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'reservations', 'notes', 'managing', 'facilitating',
        'passports', 'visas', 'uploads', 'accolades', 'fundraisers',
        'medical_releases'
    ];

    /**
     * Turn this item object into a generic array
     *
     * @param User $user
     * @return array
     */
    public function transform(User $user)
    {
        $user->load('banner', 'avatar');

        return [
            'id'           => $user->id,
            'name'         => $user->name,
            'email'        => $user->email,
            'password'     => $user->password,
            'alt_email'    => $user->alt_email,
            'gender'       => $user->gender,
            'status'       => $user->status,
            'birthday'     => $user->birthday ? $user->birthday->toDateString() : null,
            'phone_one'    => $user->phone_one,
            'phone_two'    => $user->phone_two,
            'street'       => $user->address,
            'city'         => $user->city,
            'state'        => $user->state,
            'zip'          => $user->zip,
            'country_code' => $user->country_code,
            'country_name' => country($user->country_code),
            'timezone'     => $user->timezone,
            'bio'          => $user->bio,
            'url'          => $user->url,
            'avatar'       => $user->avatar ? image($user->avatar->source) : null,
            'banner'       => $user->banner ? image($user->banner->source) : null,
            'public'       => (bool) $user->public,
            'created_at'   => $user->created_at->toDateTimeString(),
            'updated_at'   => $user->updated_at->toDateTimeString(),
            'links'        => [
                [
                    'rel' => 'self',
                    'uri' => '/users/' . $user->id,
                ]
            ],
        ];
    }

    public function includeAccolades(User $user)
    {
        $accolades = $user->accolades;

        return $this->collection($accolades, new AccoladeTransformer);
    }

    public function includeUploads(User $user)
    {
        $uploads = $user->uploads;

        return $this->collection($uploads, new UploadTransformer);
    }

    /**
     * Include Reservations
     *
     * @param User $user
     * @return \League\Fractal\Resource\Item
     */
    public function includeReservations(User $user)
    {
        $reservations = $user->reservations;

        return $this->collection($reservations, new ReservationTransformer);
    }

    public function includePassports(User $user)
    {
        $passports = $user->passports;

        return $this->collection($passports, new PassportTransformer);
    }

    public function includeMedicalReleases(User $user)
    {
        $releases = $user->medicalReleases();

        return $this->collection($releases, new MedicalReleaseTransformer);
    }

    public function includeVisas(User $user)
    {
        $visas = $user->visas;

        return $this->collection($visas, new VisaTransformer);
    }

    /**
     * Include Notes
     *
     * @param User $user
     * @return \League\Fractal\Resource\Item
     */
    public function includeNotes(User $user)
    {
        $notes = $user->notes;

        return $this->collection($notes, new NoteTransformer);
    }

    /**
     * Include Groups Managing
     *
     * @param User $user
     * @return \League\Fractal\Resource\Collection
     */
    public function includeManaging(User $user)
    {
        $groups = $user->managing;

        return $this->collection($groups, new GroupTransformer);
    }

    /**
     * Include Trips Facilitating
     *
     * @param User $user
     * @return \League\Fractal\Resource\Collection
     */
    public function includeFacilitating(User $user)
    {
        $trips = $user->facilitating;

        return $this->collection($trips, new TripTransformer);
    }

}