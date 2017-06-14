<?php

namespace App\Models\v1;

use App\RoomCount;
use App\UuidForKey;
use App\OccupantCount;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use App\Services\Rooming\ValidatesRooms;
use Illuminate\Database\Eloquent\SoftDeletes;

class Accommodation extends Model
{
    use Filterable, UuidForKey, SoftDeletes;

    protected $guarded = [];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function campaign()
    {
        return $this->region->campaign();
    }

    public function notes()
    {
        return $this->morphMany(Note::class, 'noteable');
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
        return $this->belongsToMany(RoomType::class, 'accommodation_room_type')
                    ->withPivot('available_rooms')
                    ->withTimestamps();
    }

    public function validateRooms($rooms)
    {
        return new ValidatesRooms($rooms, $this);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower(trim($value));
    }

    public function getNameAttribute($value)
    {
        return ucwords($value);
    }

}
