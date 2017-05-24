<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Region extends Model
{
    use Filterable, UuidForKey, SoftDeletes;

    protected $guarded = [];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim(strtolower($value));
    }

    public function setCallsignAttribute($value)
    {
        if ($value)
            $this->attributes['callsign'] = trim(strtolower($value));
    }

    public function getNameAttribute($value)
    {
        return ucwords($value);
    }

    public function getCallsignAttribute($value)
    {
        if ($value)
            return ucwords($value);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
