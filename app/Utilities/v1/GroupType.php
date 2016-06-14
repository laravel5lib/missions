<?php

namespace App\Utilities\v1;

class GroupType {

    /**
     * Available group types
     *
     * @var array
     */
    protected static $types = [
        'church','business','nonprofit','youth','other'
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

}