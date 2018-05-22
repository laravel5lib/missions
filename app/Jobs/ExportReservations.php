<?php

namespace App\Jobs;

use App\Models\v1\Reservation;

class ExportReservations extends Exporter
{
    public function data(array $request)
    {
        $reservations = Reservation::filter(array_filter($request))
            ->with('user', 'trip.campaign', 'trip.group', 'requirements.requirement', 'priceables', 'deadlines', 'promocodes')
            ->get();

        return $reservations;
    }

    public function columns($reservation)
    {
        $columns = [
            'managing_user' => $reservation->user->name,
            'user_email' => $reservation->user->email,
            'user_primary_phone' => $reservation->user->phone_one,
            'user_secondary_phone' => $reservation->user->secondary_phone,
            'group' => $reservation->trip->group->name,
            'trip_type' => $reservation->trip->type,
            'campaign' => $reservation->trip->campaign->name,
            'country_located' => country($reservation->trip->campaign->country_code),
            'start_date' => $reservation->trip->started_at->format('M d, Y'),
            'end_date' => $reservation->trip->ended_at->format('M d, Y'),
            'given_names' => $reservation->given_names,
            'surname' => $reservation->surname,
            'gender' => $reservation->gender,
            'marital_status' => $reservation->status,
            'shirt_size' => shirtSize($reservation->shirt_size),
            'age' => $reservation->age,
            'birthday' => $reservation->birthday->format('M d, Y'),
            'email' => $reservation->email,
            'primary_phone' => $reservation->phone_one,
            'secondary_phone' => $reservation->phone_two,
            'street_address' => $reservation->address,
            'city' => $reservation->city,
            'state_providence' => $reservation->state,
            'zip_postal' => $reservation->zip,
            'country' => country($reservation->country_code),
            'percent_raised' => $reservation->getPercentRaised().'%',
            'amount_raised' => $reservation->totalRaisedInDollars(),
            'outstanding' => $reservation->totalOwedInDollars(),
            'desired_role' => teamRole($reservation->desired_role),
            'companions' => $this->getCompanions($reservation->companionReservations),
            'requirements' => $this->getRequirements($reservation->requirements),
            'deadlines' => $this->getDeadlines($reservation->deadlines),
            'promocodes' => $this->getPromocodes($reservation->promocodes),
            'designation' => $this->getDesignation($reservation->designation),
            'registered_at' => $reservation->created_at->toCookieString(),
            'updated_at' => $reservation->updated_at->toCookieString(),
            'dropped_at' => $reservation->dropped_at ? $reservation->dropped_at->toCookieString() : null
        ];

        return $columns;
    }

    private function getCompanions($companions)
    {
        $array = $companions->map(function ($companion) {
            return $companion->given_names . ' '
                    . $companion->surname
                    . '('. $companion->pivot->relationship .')';
        })->all();

        $companions = implode(", ", $array);

        return $companions;
    }

    private function getRequirements($requirements)
    {
        $array = $requirements->map(function ($requirement) {
            return $requirement->requirement->name . ' ('.$requirement->status.')';
        })->all();

        $requirements = implode(", ", $array);

        return $requirements;
    }

    private function getDeadlines($deadlines)
    {
        $array = $deadlines->map(function ($deadline) {
            return $deadline->name . ' ('.$deadline->date->format('M d, Y').')';
        })->all();

        $deadlines = implode(", ", $array);

        return $deadlines;
    }

    private function getPromocodes($promocodes)
    {
        $array = $promocodes->map(function ($promo) {
            return $promo->code;
        })->all();

        $promocodes = implode(", ", $array);

        return $promocodes;
    }

    private function getDesignation($designation = null)
    {
        if (is_null($designation)) {
            return 'none';
        }

        $designation = array_flatten($designation->content);

        return implode('', $designation);
    }
}
