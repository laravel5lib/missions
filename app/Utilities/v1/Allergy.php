<?php

namespace App\Utilities\v1;

class Allergy {

    /**
     * Available allergies
     *
     * @var array
     */
    protected static $allergies = [
        'Peanuts',
        'Milk',
        'Eggs',
        'Wheat',
        'Fruit',
        'Nuts',
        'Fish',
        'Pollen',
        'Dust Mites',
        'Mold',
        'Animal Dander',
        'Insect Sting',
        'Latex',
        'Certain Drugs'
    ];

    public static function all()
    {
        return static::$allergies;
    }

    /**
     * Return a comma separated string of allergies
     *
     * @return string
     */
    public static function lists()
    {

        return implode(',', array_flatten(static::all()));
    }
}