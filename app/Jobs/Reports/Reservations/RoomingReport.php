<?php

namespace App\Jobs\Reports\Reservations;

use App\Jobs\Job;
use App\Models\v1\Reservation;
use App\Services\Reports\CSVReport;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RoomingReport extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $request;
    private $user;

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
     * @param Reservation $reservation
     * @return void
     */
    public function handle(Reservation $reservation)
    {
        $reservations = $reservation->filter(array_filter($this->request))
            ->withCount('companionReservations')
            ->with('rooms.type', 'rooms.plans', 'costs',
                'trip.group', 'trip.campaign')
            ->get();

        $data = $this->columnize($reservations);

        $filename = 'reservations_rooming_' . time();

        (new CSVReport($data, $this->user))->make($filename)->notify();
    }

    public function columnize($reservations)
    {
//        $companionCols = [];
//
//        foreach(range(1, $reservations->max('companion_reservations_count')) as $number)
//        {
//            $companionCols['Companion '.$number . ' Given Names'] = null;
//            $companionCols['Companion '.$number . ' Surname'] = null;
//        }
//
//        $roommateCols = [];
//
//        foreach(range(1, $reservations->pluck('rooms')->flatten()->max('occupants_count')) as $number)
//        {
//            $roommateCols['Roommate '.$number . ' Given Names'] = null;
//            $roommateCols['Roommate '.$number . ' Surname'] = null;
//        }

        return $reservations->map(function($reservation) {

//            foreach($reservation->companionReservations as $key => $companion)
//            {
//                $number = ($key+1);
//                $companionCols['Companion '. $number .' Surname'] = $companion->surname;
//                $companionCols['Companion '. $number .' Given Names'] = $companion->given_names;
//            }
//
//            foreach($reservation->rooms->pluck('occupants')->flatten() as $key => $roommate)
//            {
//                $number = ($key+1);
//                $roommateCols['Roommate '. $number .' Surname'] = $roommate->surname;
//                $roommateCols['Roommate '. $number .' Given Names'] = $roommate->given_names;
//            }

            $data = [
                'Campaign' => $reservation->trip->campaign->name,
                'Group' => $reservation->trip->group->name,
                'Trip Type' => $reservation->trip->type,
                'Surname' => $reservation->surname,
                'Given Names' => $reservation->given_names,
                'Age' => $reservation->age,
                'Gender' => $reservation->gender,
                'Marital Status' => $reservation->status,
                'Designation' => $reservation->designation ?
                    implode('', array_flatten($reservation->designation->content)) : 'none',
                'Rooming Cost(s)' => implode(", ", $reservation->costs()->type('optional')->get()->map(function($cost) {
                    return $cost->name;
                })->all()),
                'Rooms' => implode(', ', $reservation->rooms->pluck('type.name')->all()),
                'Plans' => implode(', ', $reservation->rooms->pluck('plans')->flatten()->pluck('name')->all())
            ];

            return $data;
        })->all();
    }

//    private function getCompanions($companions)
//    {
//        $array = $companions->map(function($companion) {
//            return $companion->given_names . ' '
//                . $companion->surname
//                . '('. $companion->pivot->relationship .')';
//        })->all();
//
//        $companions = implode(", ", $array);
//
//        return $companions;
//    }
}
