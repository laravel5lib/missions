<?php

namespace App\Utilities\v1;

class Role {

    protected static $leadership = [
        'GPLR' => 'Group Leader',
        'TMLR' => 'Team Leader',
        'PRDR' => 'Project Director',
        'PRAS' => 'Project Assistant',
        'CODR' => 'Country Director',
        'SSPK' => 'Stadium Speaker',
        'MCDR' => 'Medical Clinic Director'
    ];

    protected static $general = [
        'MISS' => 'Missionary',
        'PAST' => 'Pastor',
        'POLI' => 'Politican',
        'BUSP' => 'Business Professional',
        'MEDI' => 'Media Professional',
        'MDPF' => 'Medical Professional'
    ];

    protected static $medical = [
        'MDPF' => 'Medical Professional',
        'RESP' => 'Respitory Therapist',
        'PHYA' => 'Physican Assistant',
        'PHYT' => 'Physical Therapist',
        'PHAT' => 'Pharmacy Tech',
        'PHAA' => 'Pharmacy Assistant',
        'PHAR' => 'Pharmacist',
        'OTEC' => 'Optometry Tech',
        'ODOC' => 'Optometry Doctor',
        'OAST' => 'Optical Assistant',
        'NULC' => 'Nutrionist/Lactation',
        'NAST' => 'Nurse Assistant',
        'NTEC' => 'Nurse Tech',
        'NPRC' => 'Nurse Practitioner',
        'REGN' => 'Nurse (RN)',
        'LPNN' => 'Nurse (LPN)',
        'NCRT' => 'Non-Certified',
        'MEDP' => 'Medical Practitioner',
        'MEDA' => 'Medical Assistant',
        'LVNN' => 'LVN',
        'HEDU' => 'Health Education',
        'ETEC' => 'EMT',
        'MDFG' => 'Doctor M.D., F.A.C.O.G.',
        'MDOC' => 'Doctor (MD)',
        'DDOC' => 'Doctor (DO)',
        'DENT' => 'Dentist (DDS)',
        'DENH' => 'Dental Hygienist',
        'DENA' => 'Dental Assistant',
        'CHRA' => 'Chiropractor Assistant',
        'CHRO' => 'Chiropractor'
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
        $result = array_where(static::all(), function($key, $value) use($code) {
            return $key === strtoupper($code);
        });

        return $result;
    }
    
}