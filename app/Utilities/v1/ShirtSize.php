<?php

namespace App\Utilities\v1;

class ShirtSize {

    /**
     * Available trip types
     *
     * @var array
     */
    protected static $sizes = [
        'XS' => 'Extra Small',
        'S' => 'Small',
        'M' => 'Medium',
        'L' => 'Large',
        'XL' => 'Extra Large',
        'XXL' => 'Extra Large x2',
        'XXXL' => 'Extra Large x3'
    ];

    /**
     * Get all trip types
     *
     * @return array
     */
    public static function all()
    {
        return static::$sizes;
    }

    /**
     * Get a single shirt by size
     *
     * @param $size
     * @return array
     */
    public static function get($size)
    {
        $result = array_where(static::all(), function($key) use($size) {
            return $key === strtoupper($size);
        });

        return $result;
    }

    public static function sizes()
    {
        return implode(',', array_flatten(array_keys(static::all())));
    }

}