<?php

namespace App\Utilities\v1;

class TripType {

    /**
     * Available trip types
     *
     * @var array
     */
    protected static $types = [
        'FUL' => 'Full Week',
        'SRT' => 'Short Trip',
        'MED' => 'Medical'
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
     * Get a single type by id
     *
     * @param $id
     * @return array
     */
    public static function get($id)
    {
        $result = array_where(static::all(), function($key) use($id) {
            return $key === strtoupper($id);
        });

        return $result;
    }

}