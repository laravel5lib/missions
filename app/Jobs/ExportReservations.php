<?php

namespace App\Jobs;

use App\Models\v1\Reservation;

class ExportReservations extends Exporter
{
    public function data($request)
    {
        $reservations = Reservation::filter($request)
            ->with('user', 'trip.campaign', 'trip.group', 'requirements.requirement', 'costs', 'dues.payment', 'deadlines')
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
            'payments' => implode(", ", $reservation->dues->map(function($due) {
                return $due->payment->cost->name. ' [balance: $'.number_format($due->outstanding_balance,2).'] ('.$due->getStatus().')';
            })->all()),
            'applied_costs' => implode(", ", $reservation->costs->map(function($cost) {
                return $cost->name . ' ($'.number_format($cost->amount,2).')';
            })->all()),
            'requirements' => implode(", ", $reservation->requirements->map(function($requirement) {
                return $requirement->requirement->name . ' ('.$requirement->status.')';
            })->all()),
            'deadlines' => implode(", ", $reservation->deadlines->map(function($deadline) {
                return $deadline->name . ' ('.$deadline->date->format('M d, Y').')';
            })->all())
        ];

        return $columns;
    }
}
