<?php

namespace App\Utilities\v1;

class Airline
{

    /**
     * All available airlines
     *
     * @var array
     */
    protected static $airlines = [
        'AS' => 'Alaska Airlines',
        'G4' => 'Allegiant Air',
        'AA' => 'American Airlines',
        'DL' => 'Delta Air Lines',
        'F9' => 'Frontier Airlines',
        'HA' => 'Hawaiian Airlines',
        'B6' => 'JetBlue Airways',
        'WN' => 'Southwest Airlines',
        'NK' => 'Spirit Airlines',
        'SY' => 'Sun Country Airlines',
        'UA' => 'United Airlines',
        'VX' => 'Virgin America',
        'AC' => 'Air Canada',
        'TS' => 'Air Transat',
        'WS' => 'WestJet',
        'PD' => 'Porter',
        'BA' => 'British Airways',
        'VS' => 'Virgin Atlantic Airways',
        'LX' => 'Swiss International Air Lines',
        'QF' => 'Qantas',
        'VA' => 'Virgin Australia International Airlines',
        'EK' => 'Emirates',
        'EY' => 'Etihad'

        // TODO add more airlines
        // TODO divide airlines array by country
    ];

    /**
     * Return all airlines
     *
     * @return array
     */
    public static function all()
    {
        return static::$airlines;
    }

    /**
     * Return a single country by iata
     *
     * @param  sting $iata
     * @return array
     */
    public static function get($iata)
    {
        $result = array_where(static::$airlines, function ($key, $value) use ($iata) {
            return $key === strtoupper($iata);
        });

        return $result;
    }

    // TODO add methods to return airlines by country
}
