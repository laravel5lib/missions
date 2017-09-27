<?php

namespace App\Utilities\v1;

class Treatment
{

    protected static $certifications = [
        'C01' => 'register patients and record chief concern, medical and family histories',
        'C02' => 'take blood pressure readings and record vitals',
        'C03' => 'take blood glucose readings',
        'C04' => 'give injections',
        'C05' => 'distribute eyeglasses',
        'C06' => 'distribute pharmaceuticals',
        'C07' => 'assist in the dental clinic',
        'C08' => 'conduct dental cleanings',
        'C09' => 'run dental sterilization',
        'C10' => 'educate patient on hygiene and diet'
    ];

    protected static $responsibilities = [
        'R01' => 'patient registration',
        'R02' => 'dental sterilization',
        'R03' => 'dental assisting',
        'R04' => 'patient education',
        'R05' => 'pharmacy assistant',
        'R06' => 'eyeglass clinic'
    ];

    /**
     * Return certifications
     *
     * @return array
     */
    public static function certifications()
    {
        return static::$certifications;
    }

    /**
     * Return responsibilities
     *
     * @return array
     */
    public static function responsibilities()
    {
        return static::$responsibilities;
    }

    /**
     * Return all treatments
     */
    public static function all()
    {
        return array_merge(static::$certifications, static::$responsibilities);
    }

    /**
     * Return a single treatment by code
     *
     * @param  sting $code
     * @return array
     */
    public static function get($code)
    {
        $result = array_where(static::all(), function ($value, $key) use ($code) {
            return $key === strtoupper($code);
        });

        return $result;
    }
}
