<?php

namespace App\Services;

class ReferralCode {

    /**
     * @var
     */
    public $prefix;

    /**
     * @var
     */
    public $entropy;

    /**
     * @param string $prefix
     * @param bool $entropy
     */
    public function __construct($prefix = '', $entropy = false)
    {
        $this->code = uniqid($prefix, $entropy);
    }

    /**
     * Limit the code by a number of characters
     * 
     * @param $length
     * @param int $start
     * @return $this
     */
    public function limit($length, $start = 0)
    {
        $this->code = substr($this->code, $start, $length);

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->code;
    }
} 