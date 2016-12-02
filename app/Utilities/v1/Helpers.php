<?php

use App\Models\v1\Campaign;
use App\Models\v1\Project;
use App\Models\v1\ProjectCause;
use App\Models\v1\Reservation;
use App\Models\v1\Trip;
use Carbon\Carbon;

function country($code)
{
    return implode('', array_keys(App\Utilities\v1\Country::get($code)));
}

function stripPhone($phone)
{
    return preg_replace('/\D+/', '', $phone);
}

function now()
{
    return Carbon::now();
}

function carbon($date)
{
    return new Carbon($date);
}

function image($source)
{
    return url('api/'.$source);
}

function generateFundName($data)
{
    if ($data instanceof Reservation) {
        return generateFundNameFromReservation($data);
    }

    if ($data instanceof Trip) {
        return generateFundNameFromTrip($data);
    }

    if ($data instanceof Campaign) {
        return $data->name . ' Campaign';
    }
}

function generateFundNameFromReservation($reservation)
{
    $name = $reservation->name . '\'s';
    $country = country($reservation->trip->country_code);
    $year = $reservation->trip->started_at->format('Y');

    return $name . ' Trip to ' . $country . ' ' . $year;
}

function generateFundNameFromTrip($trip)
{
    $name = $trip->group->name . '\'s';
    $type = $trip->type;
    $country = country($trip->country_code);
    $year = $trip->started_at->format('Y');

    return $name . ' ' . ucwords($type) . ' Trip to ' . $country . ' ' . $year;
}

function generateFundraiserName($data)
{
    if ($data instanceof Reservation) {
        return generateFundraiserNameFromReservation($data);
    }

    if ($data instanceof Trip) {
        return generateFundraiserNameFromTrip($data);
    }
}

function generateFundraiserNameFromReservation($reservation)
{
   return 'Send ' . $reservation->name . ' to ' . country($reservation->trip->country_code);
}

function generateFundraiserNameFromTrip($trip)
{
    return 'Send ' . $trip->group->name . ' to ' . country($trip->country_code);
}

function set_active($path, $active = 'active')
{
    return Request::is($path) ? $active : '';
}

/**
 * Generate a Class Name for QuickBooks
 *
 * @param $data
 * @return string
 */
function generateQbClassName($data)
{
    if ($data instanceof Reservation) {
        return $data->trip->campaign->name . ' - Team';
    }

    if ($data instanceof Trip) {
       return $data->campaign->name . ' - Team';
    }

    if ($data instanceof Campaign) {
        return $data->name . ' - General';
    }
}