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
        'reservations', 'notes', 'managing', 'facilitating'
    ];

    /**
     * Turn this item object into a generic array
     *
     * @param User $user
     * @return array
     */
    public function transform(User $user)
    {
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
            'street'       => $user->street,
            'city'         => $user->city,
            'state'        => $user->state,
            'zip'          => $user->zip,
            'country_code' => $user->country,
            'country_name' => country($user->country),
            'timezone'     => $user->timezone,
            'bio'          => $user->bio,
            'url'          => $user->url,
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
        $groups = $user->groupsManaging;

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
        $trips = $user->tripsFacilitating;

        return $this->collection($trips, new TripTransformer);
    }

}