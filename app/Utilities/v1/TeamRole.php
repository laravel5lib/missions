<?php

namespace App\Utilities\v1;

class TeamRole
{

    protected static $leadership = [
        'GPLR' => 'Group Leader',
        'TMLR' => 'Squad Leader',
        'PRDR' => 'Project Director',
        'PRAS' => 'Project Assistant',
        'CODR' => 'Country Director',
        'SSPK' => 'Stadium Speaker',
        'MCDR' => 'Medical Clinic Director',
        'INFL' => 'Influencer',
        'PAST' => 'Pastor'
    ];

    protected static $general = [
        'MISS' => 'Missionary (13+)',
        'MINR' => 'Missionary (Child 8-12)',
        'POLI' => 'Politican',
        'BUSP' => 'Business Professional',
        'MEDI' => 'Media Professional',
        'MDPF' => 'Medical Professional',
        'WATR' => 'Clean Water Team Member',
        'ATHL' => 'Athlete',
        'FIRE' => 'Firefighter'
    ];

    protected static $medical = [
        'MDPF' => 'Medical Professional',
        'MDSG' => 'Medical Student',
        'MDSN' => 'Medical Student: Nursing',
        'MDSP' => 'Medical Student: Pre-Med',
        'MDSD' => 'Medical Student: Dental',
        'RESP' => 'Respitory Therapist',
        'PHYS' => 'Physician',
        'PHYA' => 'Physician\'s Assistant',
        'PHYT' => 'Physical Therapist',
        'PHAT' => 'Pharmacy Tech',
        'PHAA' => 'Pharmacy Assistant',
        'PHAR' => 'Pharmacist',
        'OTEC' => 'Optometry Tech',
        'ODOC' => 'Optometry Doctor',
        'OAST' => 'Optical Assistant',
        'DIET' => 'Dietitian',
        'NUTR' => 'Nutrionist',
        'LACT' => 'Lactation Consultant',
        'NAST' => 'Nurse Assistant',
        'NTEC' => 'Nurse Tech',
        'NPRC' => 'Nurse Practitioner',
        'REGN' => 'Nurse (RN)',
        'LPNN' => 'Nurse (LPN)',
        'NCRT' => 'Non-Certified',
        'MEDA' => 'Medical Assistant',
        'LVNN' => 'LVN',
        'HEDU' => 'Health Education',
        'ETEC' => 'EMT',
        'MDFG' => 'Doctor (OB/GYN)',
        'MDOC' => 'Doctor (MD)',
        'DDOC' => 'Doctor (DO)',
        'DENT' => 'Dentist (DDS)',
        'DENH' => 'Dental Hygienist',
        'DENA' => 'Dental Assistant',
        'CHRA' => 'Chiropractor Assistant',
        'CHRO' => 'Chiropractor',
        'RDIO' => 'Radiologist',
        'CRDO' => 'Cardiologist',
        'ANES' => 'Anesthesiologist',
        'PRAY' => 'Prayer Team',
        'PHLE' => 'Phlebotomist',
        'STEC' => 'Surgical Tech'
    ];

    /**
     * Return all roles
     *
     * @return array
     */
    public static function all()
    {
        $array = array_merge(static::$leadership, static::$general);
        $all = array_merge($array, static::$medical);

        return $all;
    }

    /**
     * Return leadership roles
     *
     * @return array
     */
    public static function leadership()
    {
        return static::$leadership;
    }

    /**
     * Return general roles
     *
     * @return array
     */
    public static function general()
    {
        return static::$general;
    }

    /**
     * Return medical roles
     *
     * @return array
     */
    public static function medical()
    {
        return static::$medical;
    }

    /**
     * Return a single role by code
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

    /**
     * Return a single role by code
     *
     * @param  sting $code
     * @return array
     */
    public static function get_code($name)
    {
        if (starts_with($name, 'Medical: ')) {
            $name = str_ireplace('Medical: ', '', $name);
        }

        if (starts_with($name, 'Medical Student: ')) {
            $name = 'Medical Student';
        }

        $result = array_where(static::all(), function ($value, $key) use ($name) {
            return $value === trim($name);
        });

        if (! $result) {
            return 'MISS';
        }

        return array_keys($result)[0];
    }

    public static function toJson()
    {
        $roles = [];

        foreach(static::all() as $key => $value) {
            array_push($roles, ['value' => $key, 'label' => $value]);
        }

        return $roles;
    }
}
