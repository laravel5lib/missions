<?php

use Carbon\Carbon;
use App\Models\v1\Fund;
use App\Models\v1\Slug;
use App\Models\v1\Trip;
use App\Models\v1\Project;
use App\Models\v1\Campaign;
use App\Services\Accounting;
use App\Models\v1\Fundraiser;
use App\Models\v1\Reservation;
use App\Models\v1\ProjectCause;

function country($code)
{
    return implode('', array_keys(App\Utilities\v1\Country::get($code)));
}

function country_code($name)
{
    return App\Utilities\v1\Country::getCode($name);
}

function teamRole($code)
{
    return implode(array_values(App\Utilities\v1\TeamRole::get($code)), '');
}

function shirtSize($size)
{
    return implode(array_values(App\Utilities\v1\ShirtSize::get($size)), '');
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

function display_file($source)
{
    return url('api/files/'.$source);
}

function download_file($source)
{
    return url('api/download/'.$source);
}

function play_file($source) {
    return url('api/play/'.$source);
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

    if ($data instanceof Project) {
        return generateFundraiserNameFromProject($data);
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

function generateFundraiserNameFromProject($project)
{
    return $project->name.' '.'Project';
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

function getAccountingClass($instance)
{
    $accounting = new Accounting;

    $class = $accounting->getOrMakeClass($instance);

    return $class;
}

function getAccountingItem($instance)
{
    $accounting = new Accounting;

    $class = $accounting->getOrMakeItem($instance);

    return $class;
}

/**
 * Remove http from url.
 * Use protocol relative.
 * @param $url
 * @return mixed
 */
function remove_http($url) {

    // check for protocol and remove
    $disallowed = array('http://', 'https://');
    foreach($disallowed as $d) {
        if(strpos($url, $d) === 0) {
            $url = str_replace($d, '//', $url);
        }
    }

    // add relative protocol if missing
    if(strpos($url, '//') === false) {
        $url = '//'.$url;
    }

    return $url;
}

/**
 * Generate a unique slug
 * 
 * @param  String $string
 * @return String
 */
function generate_slug($string)
{
    $slug = str_slug($string);

    $count = Slug::whereRaw("url RLIKE '^{$slug}(-[0-9]+)?$'")->count();

    return $count ? "{$slug}-{$count}" : $slug;
}

/**
 * Generate a unique fund slug
 * 
 * @param  String $string
 * @return String
 */
function generate_fund_slug($string)
{
    $slug = str_slug($string);

    $count = Fund::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();

    return $count ? "{$slug}-{$count}" : $slug;
}

/**
 * Generate a unique fundraiser slug
 * 
 * @param  String $string
 * @return String
 */
function generate_fundraiser_slug($string)
{
    $slug = str_slug($string);

    $count = Fundraiser::whereRaw("url RLIKE '^{$slug}(-[0-9]+)?$'")->count();

    return $count ? "{$slug}-{$count}" : $slug;
}

/**
 * Converts a height value given in feet/inches to cm
 *
 * @param int $feet
 * @param int $inches
 * @return int
 */
function convert_to_cm($feet, $inches = 0) {
    $inches = ($feet * 12) + $inches;
    return (int) round($inches / 0.393701);
}

/**
 * Converts a height value given in cm to feet and inches
 *
 * @param int $cm
 * @return array
 */
function convert_to_inches($cm) {
    $inches = round($cm * 0.393701);
    $result = [
        'ft' => intval($inches / 12),
        'in' => $inches % 12,
    ];

    return $result;
}

/**
 * Converts a weight value given in pounds
 * 
 * @param  integer $pounds
 * @return integer
 */
function convert_to_kg($pounds)
{
    return round($pounds * 0.4535); // kilogram per pound
}

/**
 * Converts a weight value given in kilograms
 * 
 * @param  integer $kg 
 * @return integer
 */
function convert_to_pounds($kg)
{
    return round($kg * 2.20462); // pounds per kilogram
}

function convert_to_cents($dollars)
{
    //
}

function removeNonNumericCharacters($value)
{
    return preg_replace("/[^0-9,.]/", "", $value);
}

function addLeadingZeros($value, $digits = 4)
{
    return str_pad($value, 4, '0', STR_PAD_LEFT);
}

function getFirstName($givenNames)
{
    $array = explode(' ',trim($givenNames));

    if (empty($array)) return null;

    return $array[0];
}
