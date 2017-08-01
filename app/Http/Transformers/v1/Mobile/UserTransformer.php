<?php

namespace App\Http\Transformers\v1\Mobile;

use App\Models\v1\User;
use Intervention\Image\Facades\Image;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{

    /**
     * Turn this item object into a generic array
     *
     * @param User $user
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id'          => $user->id,
            'name'        => $user->name,
            'email'       => $user->email,
            'created_at'  => $user->created_at->toDateTimeString(),
            'updated_at'  => $user->updated_at->toDateTimeString(),
            'trips'       => $this->includeReservations($user),
            'links'       => [
                [
                    'rel' => 'self',
                    'uri' => '/users/' . $user->id,
                ]
            ],
            'profile_pic' => $user->avatar ? Image::make(image($user->avatar->source.'?w=200&fm=jpg&q=25'))->encode('data-url') : null,
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
        $reservations = $user->reservations()
            ->has('membership')
            ->with('membership.team.region', 'trip.campaign')
            ->get();

        $mobileTrips = $reservations->map(function ($reservation) {
            $year = $reservation->trip->started_at->format('Y');
            $region = $reservation->membership->team->region;
            $name = $reservation->name;

            return [
                'label'          => $year . ' ' . $region->name . ' (' . $name . ')',
                'state'          => $region->name,
                'country'        => country($reservation->trip->country_code),
                'campaign'       => $reservation->trip->campaign->name,
                'role'           => null,
                'team_number'    => $reservation->membership->team->call_sign,
                'forms'          => $reservation->membership->forms,
                'reservation_id' => $reservation->id,
                'trip_id'        => $reservation->trip_id,
                'team_id'        => $reservation->membership->team->id,
                'state_id'       => $region->id,
                'campaign_id'    => $reservation->trip->campaign_id
            ];
        });

        return $mobileTrips;
    }
}
