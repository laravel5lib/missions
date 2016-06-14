<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\TeamMember;
use League\Fractal\TransformerAbstract;

class TeamMemberTransformer extends TransformerAbstract {

    /**
     * List of resources available to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'reservation', 'team'
    ];

    /**
     * Transform the object into a basic array
     *
     * @param TeamMember $member
     * @return array
     */
    public function transform(TeamMember $member)
    {
        $member->load('reservation.companions');

        return [
            'id'             => $member->id,
            'name'           => $member->reservation->name,
            'age'            => $member->reservation->age,
            'gender'         => $member->reservation->gender,
            'status'         => $member->reservation->status,
            'team_id'        => $member->team_id,
            'reservation_id' => $member->reservation_id,
            'companions'     => (int) $member->reservation->companions()->count(),
            'role_id'        => (int) $member->role_id,
            'group'          => $member->group,
            'leader'         => (bool) $member->leader,
            'created_at'     => $member->created_at->toDateTimeString(),
            'updated_at'     => $member->updated_at->toDateTimeString(),
            'links'          => [
                [
                    'rel' => 'self',
                    'uri' => '/teams/' . $member->team_id . '/members/' . $member->id,
                ]
            ]
        ];
    }

    /**
     * Include Reservation
     *
     * @param TeamMember $member
     * @return \League\Fractal\Resource\Item
     */
    public function includeReservation(TeamMember $member)
    {
        $reservation = $member->reservation;

        return $this->item($reservation, new ReservationTransformer);
    }

    /**
     * Include Team
     *
     * @param TeamMember $member
     * @return \League\Fractal\Resource\Item
     */
    public function includeTeam(TeamMember $member)
    {
        $team = $member->team;

        return $this->item($team, new TeamTransformer);
    }
}