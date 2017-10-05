<?php

namespace App\Models\v1;

use App\Models\v1\Trip;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Representative extends Model
{
    use Filterable;

    public $guarded = [];

    public function trip()
    {
        return $this->hasMany(Trip::class, 'rep_id');
    }

    protected function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value);
    }

    protected function getNameAttribute($value)
    {
        return ucwords($value);
    }
}
