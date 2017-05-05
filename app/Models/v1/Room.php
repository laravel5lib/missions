<?php

namespace App\Models\v1;

use App\UuidForKey;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use UuidForKey, SoftDeletes, Filterable;
    
    protected $guarded = [];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function type()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id');
    }

    public function plans()
    {
        return $this->morphedByMany(RoomingPlan::class, 'roomable');
    }

    public function occupants()
    {
        return $this->belongsToMany(Reservation::class, 'occupants')
                    ->withPivot('room_leader')
                    ->withTimestamps();
    }
}
