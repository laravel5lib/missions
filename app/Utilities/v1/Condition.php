<?php

namespace App\Utilities\v1;

class Condition {

    /**
     * Available conditions.
     *
     * @var array
     */
    protected static $conditions = [
        'Amoebic Dysentery',
        'Anxiety',
        'Asthma',
        'Back Pain',
        'Depression',
        'Diabetes',
        'Epilepsy',
        'Fever',
        'Foot/Leg Difficulties',
        'Gastro Intestinal',
        'Heart',
        'Hepatitis',
        'HIV/AIDS',
        'Hypertension',
        'Hypoclycemia',
        'Infectious Mononucleosis',
        'Kidney Trouble',
        'Malaria',
        'Mental Disorder',
        'Migraine Headache',
        'Muscle Pain',
        'Paralysis',
        'Pneumonia',
        'Pregnancy',
        'Rheumatic',
        'Sleep Disorder',
        'Tuberculosis',
        'Ulcers'
    ];

    /**
     * Return an array of all conditions.
     *
     * @return array
     */
    public static function all()
    {
        return static::$conditions;
    }

    /**
     * Return a comma separated string of conditions.
     *
     * @return string
     */
    public static function lists()
    {

        return implode(',', array_flatten(static::all()));
    }
}