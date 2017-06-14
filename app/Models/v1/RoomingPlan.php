<?php

namespace App\Models\v1;

use App\RoomCount;
use App\UuidForKey;
use App\OccupantCount;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use App\Services\Rooming\ValidatesRooms;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomingPlan extends Model
{
    use UuidForKey, SoftDeletes, Filterable;
    
    protected $guarded = [];

    /**
     * Attributes that should be cast to date time objects
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
    
    public function rooms()
    {
        return $this->morphToMany(Room::class, 'roomable');
    }

    public function roomsCount()
    {
        return new RoomCount($this);
    }

    public function occupantsCount()
    {
        return new OccupantCount($this);
    }

    public function availableRoomTypes()
    {
        return $this->belongsToMany(RoomType::class, 'rooming_plan_room_type')
                    ->withPivot('available_rooms')
                    ->withTimestamps();
    }

    public function validateRooms($rooms)
    {
        return new ValidatesRooms($rooms, $this);
    }
}
