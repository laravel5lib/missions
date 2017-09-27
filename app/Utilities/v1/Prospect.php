<?php

namespace App\Utilities\v1;

class Prospect
{
    
    protected static $general = [
        'ADLT' => 'Adults',
        'YNGA' => 'Young Adults (18-29)',
        'TEEN' => 'Teens (13+)',
        'FAMS' => 'Families',
        'MMEN' => 'Men',
        'WMEN' => 'Women',
        'MEDI' => 'Media Professionals',
        'PSTR' => 'Pastors',
        'BUSL' => 'Business Leaders',
        'MDPF' => 'Medical Professionals'
    ];

    protected static $medical = [
        'MDPF' => 'Medical Professionals',
        'PHYS' => 'Physicians',
        'SURG' => 'Surgeons',
        'REGN' => 'Registered Nurses',
        'DENT' => 'Dentists',
        'HYGN' => 'Hygienists',
        'DENA' => 'Dental Assistants',
        'PHYA' => 'Physician Assistants',
        'NURP' => 'Nurse Practitioners',
        'PHAR' => 'Pharmacists',
        'PHYT' => 'Physical Therapists',
        'CHRO' => 'Chiropractors',
        'MSTU' => 'Medical Students',
        'DSTU' => 'Dental Students',
        'NSTU' => 'Nursing Students'
    ];

    /**
     * Return all prospects
     *
     * @return array
     */
    public static function all()
    {
        $all = array_merge(static::$general, static::$medical);

        return $all;
    }

    /**
     * Return general prospects
     *
     * @return array
     */
    public static function general()
    {
        return static::$general;
    }

    /**
     * Return medical prospects
     *
     * @return array
     */
    public static function medical()
    {
        return static::$medical;
    }

    /**
     * Return a single prospect by code
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
