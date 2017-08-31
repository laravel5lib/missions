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
        'medical_releases', 'roles', 'links', 'abilities'
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
            'address'       => $user->address,
            'city'         => $user->city,
            'state'        => $user->state,
            'zip'          => $user->zip,
            'country_code' => $user->country_code,
            'country_name' => country($user->country_code),
            'shirt_size'   => strtoupper($user->shirt_size),
            'timezone'     => $user->timezone,
            'bio'          => $user->bio,
            'url'          => $user->slug ? $user->slug->url : null,
            'avatar'       => $user->avatar ? image($user->avatar->source) : url('/images/placeholders/user-placeholder.png'),
            'avatar_upload_id' => $user->avatar_upload_id,
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

    public function includeAbilities(User $user)
    {
        $abilities = $user->getAbilities();

        return $this->collection($abilities, new AbilityTransformer);
    }

    /**
     * Include links.
     *
     * @param User $user
     * @return \League\Fractal\Resource\Collection
     */
    public function includeLinks(User $user)
    {
        $links = $user->links;

        return $this->collection($links, new LinkTransformer);
    }

    /**
     * Include accolades belonging to the user.
     *
     * @param User $user
     * @return \League\Fractal\Resource\Collection
     */
    public function includeAccolades(User $user)
    {
        $accolades = $user->accolades;

        return $this->collection($accolades, new AccoladeTransformer);
    }

    /**
     * Include uploads managed by the user.
     *
     * @param User $user
     * @return \League\Fractal\Resource\Collection
     */
    public function includeUploads(User $user)
    {
        $uploads = $user->uploads;

        return $this->collection($uploads, new UploadTransformer);
    }

    /**
     * Include reservations managed by the user.
     *
     * @param User $user
     * @return \League\Fractal\Resource\Item
     */
    public function includeReservations(User $user)
    {
        $reservations = $user->reservations;

        return $this->collection($reservations, new ReservationTransformer);
    }

    /**
     * Include passports managed by the user.
     *
     * @param User $user
     * @return \League\Fractal\Resource\Collection
     */
    public function includePassports(User $user)
    {
        $passports = $user->passports;

        return $this->collection($passports, new PassportTransformer);
    }

    /**
     * Include medical releases managed by the user.
     *
     * @param User $user
     * @return \League\Fractal\Resource\Collection
     */
    public function includeMedicalReleases(User $user)
    {
        $releases = $user->medicalReleases;

        return $this->collection($releases, new MedicalReleaseTransformer);
    }

    /**
     * Include visas managed by the user.
     *
     * @param User $user
     * @return \League\Fractal\Resource\Collection
     */
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

    /**
     * Include roles assigned to the user.
     *
     * @param User $user
     * @return \League\Fractal\Resource\Collection
     */
    public function includeRoles(User $user)
    {
        $roles = $user->roles;

        return $this->collection($roles, new RoleTransformer);
    }
}
