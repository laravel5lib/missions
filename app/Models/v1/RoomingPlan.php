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

    /**
     * Get groups
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'rooming_plan_group')
            ->withTimestamps();
    }

    /**
     * Get campaign
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Get rooms
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function rooms()
    {
        return $this->morphToMany(Room::class, 'roomable');
    }

    /**
     * Get rooms count
     *
     * @return RoomCount
     */
    public function roomsCount()
    {
        return new RoomCount($this);
    }

    /**
     * Get occupant count
     *
     * @return OccupantCount
     */
    public function occupantsCount()
    {
        return new OccupantCount($this);
    }

    /**
     * Get available room types
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function availableRoomTypes()
    {
        return $this->belongsToMany(RoomType::class, 'rooming_plan_room_type')
                    ->withPivot('available_rooms')
                    ->withTimestamps();
    }

    /**
     * Validate rooms being added to the plan
     *
     * @param $rooms
     * @return ValidatesRooms
     */
    public function validateRooms($rooms)
    {
        return new ValidatesRooms($rooms, $this);
    }
}
