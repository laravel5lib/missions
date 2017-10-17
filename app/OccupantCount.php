<?php
namespace App;

use App\Models\v1\RoomType;

class OccupantCount
{
    protected $roomable;

    function __construct($roomable)
    {
        $this->roomable = $roomable;
    }

    public function total()
    {
        return $this->roomable
                    ->rooms()
                    ->with('occupants')
                    ->get()
                    ->pluck('occupants')
                    ->flatten()
                    ->count();
    }
}
