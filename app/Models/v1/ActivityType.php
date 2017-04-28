<?php

namespace App\Models\v1;

use Illuminate\Database\Eloquent\Model;

class ActivityType extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = snake_case($value);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}
