<?php

namespace App\Jobs\Reports\Rooms;

use App\Jobs\Job;
use App\Repositories\Rooming\Interfaces\Room;
use App\Services\Reports\CSVReport;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RoomsByAccommodationReport extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $request;
    protected $user;

    /**
     * Create a new job instance.
     *
     * @param $request
     * @param $user
     */
    public function __construct($request, $user)
    {
        $this->request = $request;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @param Room $room
     * @return void
     */
    public function handle(Room $room)
    {
        $rooms = $room->filter($this->request)
                      ->with('type', 'accommodations')
                      ->withOccupants();

        $data = $this->columnize($rooms);

        $filename = 'rooms_by_plan_' . time();

        (new CSVReport($data, $this->user))->make($filename)->notify();
    }

    public function columnize($rooms)
    {
        $occupantCols = [];

        foreach(range(1, $rooms->max('occupants_count')) as $number)
        {
            $occupantCols['Occupant '.$number . ' Given Names'] = null;
            $occupantCols['Occupant '.$number . ' Surname'] = null;
        }

        return $rooms->map(function ($room) use ($occupantCols) {

            foreach($room->occupants as $key => $occupant)
            {
                $number = ($key+1);
                $occupantCols['Occupant '. $number .' Surname'] = $occupant->surname;
                $occupantCols['Occupant '. $number .' Given Names'] = $occupant->given_names;
            }

            $columns = [
                'Room Type' => $room->type->name,
                'Room Label' => $room->label,
                'Accommodation(s)' => $room->accommodations ? implode(',', $room->accommodations->pluck('name')->all()) : null
            ];

            return $occupantCols + $columns;

        })->all();
    }
}
