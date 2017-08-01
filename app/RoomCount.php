<?php
namespace App;

use App\Models\v1\RoomType;

class RoomCount
{
    protected $roomable;

    function __construct($roomable)
    {
        $this->roomable = $roomable;
    }

    public function all()
    {
        $roomCounts = $this->getRooms()->groupBy('type.name')
              ->map(function ($room) {
                  return count($room);
              })->all();

        return collect(array_merge($this->getRoomTypes(), $roomCounts))
                    ->put('total', $this->getRooms()->count())
                    ->all();
    }

    public function byType($typeId)
    {
        return $this->getRooms()->filter(function ($room) use ($typeId) {
            return $room->room_type_id === $typeId;
        })->groupBy('type.name')->map(function ($room) {
            return count($room);
        })->first();
    }

    public function getRooms()
    {
        return $this->roomable->rooms()->with('type')->get();
    }

    public function getRoomTypes()
    {
        $types = RoomType::where('campaign_id', $this->roomable->campaign->id)->get();

        return $this->getDefaultCounts($types)->all();
    }

    public function getDefaultCounts($types)
    {
        return $types->keyBy('name')->map(function ($name) {
            return 0;
        });
    }
}
