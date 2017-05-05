<?php

namespace App;

class RoomTypeRules {
    
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

    /**
     * Get rule by it's key.
     * 
     * @param  string $key
     * @return string
     */
    public function get($key)
    {
        return array_get($this->rules, $key);
    }

    /**
     * Set new rule.
     * 
     * @param array $rules
     */
    public function set($key, $value)
    {
        $this->rules[$key] = $value;

        return $this;
    }

    /**
     * Check if room type has rule
     * 
     * @param  String  $key
     * @return boolean
     */
    public function has($key)
    {
        return array_key_exists($key, $this->rules);
    }

    /**
     * Get all rules.
     * 
     * @return array
     */
    public function all()
    {
        return $this->rules;
    }

    /**
     * Merge rule attributes with existing rules.
     * 
     * @param  array  $attributes
     * @return array
     */
    public function merge(array $attributes)
    {
        $this->rules = array_merge(
            $this->rules, 
            array_only($attributes, array_keys($this->rules))
        );

        return $this;
    }

    /**
     * Return rule as property.
     * 
     * @param  string $key
     * @return string
     */
    public function __get($key)
    {
        if ($this->has($key)) {
            return $this->get($key);
        }

        throw new \Exception("the $key rule does not exist.");
    }

}