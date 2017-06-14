<?php

namespace App;

class TeamTypeRules Extends Rules {
    
    /**
     * Rules
     * 
     * @var array
     */
    protected $rules = [];

    /**
     * RoomTypeRules Constructor
     * @param array $rules
     */
    function __construct(array $rules)
    {
        $this->rules = $rules;
    }

}