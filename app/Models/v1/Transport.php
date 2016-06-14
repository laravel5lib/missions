<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class Transport extends Model
{
    use Filterable, UuidForKey;

    protected $fillable = [
        'id', 'type', 'vessel_no', 'name', 'call_sign',
        'domestic', 'capacity', 'campaign_id'
    ];

    protected $dates = [
        'departs_at', 'arrives_at', 'created_at', 'updated_at'
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function passengers()
    {
        return $this->hasMany(Passenger::class);
    }
}
