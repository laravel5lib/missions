<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hub extends Model
{
    use UuidForKey, SoftDeletes, Filterable;

    protected $guarded = [];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function activities()
    {
        return $this->morphToMany(Activity::class, 'activitable');
    }

    public function scopeCallSign($query, $callSign)
    {
        return $this->where('call_sign', $callSign);
    }
}
