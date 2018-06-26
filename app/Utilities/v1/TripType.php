<?php

namespace App\Utilities\v1;

class TripType
{

    /**
     * Available trip types
     *
     * @var array
     */
    protected static $types = [
        'ministry' => 'Ministry',
        'family' => 'Family',
        'international' => 'International',
        'media' => 'Media',
        'medical' => 'Medical',
        'leader' => 'Leader',
        'sports' => 'Sports',
        'water' => 'Water'
    ];

    /**
     * Get all trip types
     *
     * @return array
     */
    public static function all()
    {
        return static::$types;
    }

    /**
     * Get a single type by string
     *
     * @param string $string
     * @return array
     */
    public static function get($string)
    {
        $result = array_where(static::all(), function ($value, $key) use ($string) {
            return $key === strtolower($string);
        });

        return $result;
    }
}
