<?php

namespace App\Http\Transformers\v1\Medical;

use App\Http\Transformers\v1\NoteTransformer;
use App\Http\Transformers\v1\ReservationTransformer;
use App\Http\Transformers\v1\UserTransformer;
use App\Models\v1\Medical\Release;
use League\Fractal\TransformerAbstract;

class ReleaseTransformer extends TransformerAbstract
{
    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'user', 'reservations', 'notes'
    ];

    /**
     * Turn this item object into a generic array
     *
     * @param Release $release
     * @return array
     */
    public function transform(Release $release)
    {
        $array = [
            'id'                => $release->id,
            'user_id'           => $release->user_id,
            'name'              => $release->name,
            'ins_provider'      => $release->ins_provider,
            'ins_policy_no'     => $release->ins_policy_no,
            'is_risk'           => (bool) $release->is_risk,
            'conditions'        => $release->conditions,
            'allergies'         => $release->allergies,
            'emergency_contact' => $release->emergency_contact,
            'created_at'        => $release->created_at->toDateTimeString(),
            'updated_at'        => $release->updated_at->toDateTimeString(),
            'links'             => [
                [
                    'rel' => 'self',
                    'uri' => '/medical/releases/' . $release->id,
                ]
            ]
        ];

        return $array;
    }

    /**
     * Include Reservations
     *
     * @param Release $release
     * @return \League\Fractal\Resource\Item
     */
    public function includeReservations(Release $release)
    {
        $reservations = $release->reservations;

        return $this->collection($reservations, new ReservationTransformer);
    }

    /**
     * Include User
     *
     * @param Release $release
     * @return \League\Fractal\Resource\Item
     */
    public function includeUser(Release $release)
    {
        $user = $release->user;

        return $this->item($user, new UserTransformer);
    }

    /**
     * Include Notes
     *
     * @param Release $release
     * @return \League\Fractal\Resource\Item
     */
    public function includeNotes(Release $release)
    {
        $notes = $release->notes;

        return $this->collection($notes, new NoteTransformer);
    }
}