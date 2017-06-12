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
              });

        return $this->getRoomTypes()
                    ->merge($roomCounts)
                    ->put('total', $this->getRooms()->count())
                    ->all();
    }

    public function byType($typeId)
    {
        return $this->getRooms()->filter(function($room) use($typeId) {
            return $room->room_type_id === $typeId;
        })->groupBy('type.name')->map(function ($room) {
            return count($room);
        })->first();
    }

    private function getRooms()
    {
        return $this->roomable->rooms()->with('type')->get();
    }

    private function getRoomTypes()
    {
       // $types = $this->roomable->availableRoomTypes;

       $types = RoomType::where('campaign_id', $this->roomable->campaign->id)->get();

       return $this->getDefaultCounts($types);
    }

    private function getDefaultCounts($types)
    {
        return $types->keyBy('name')->map(function($name) {
            return 0;
        });
    }
}