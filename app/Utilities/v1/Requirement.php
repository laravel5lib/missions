<?php

namespace App\Utilities\v1;

class Requirement
{

    protected static $requirements = [
        'medical_release' => 'Medical Release',
        'pastoral_rec' => 'Pastoral Recommendation',
        'testimony' => 'Personal Testimony',
        'passport' => 'Passport',
        'visa' => 'Travel Visa',
        'media_creds' => 'Media Credentials',
        'medical_creds' => 'Medical Credentials',
        'itinerary' => 'Travel Itinerary',
        'yellow_fever' => 'Yellow Fever Card',
        'minor_release' => 'Minor Release'
    ];

    /**
     * Return all requirements
     *
     * @return array
     */
    public static function all()
    {
        return static::$requirements;
    }

    /**
     * Return a single requirement by code
     *
     * @param  string $code
     * @return array
     */
    public static function get($code)
    {
        $result = array_where(static::$requirements, function ($key, $value) use ($code) {
            return $key === strtolower($code);
        });

        return $result;
    }
}
