<?php

namespace App\Models\v1;

class AccountingClass extends Model
{
    protected $guarded = [];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim($value);
    }

    public function funds()
    {
        return $this->hasMany(Fund::class, 'class_id');
    }
}
