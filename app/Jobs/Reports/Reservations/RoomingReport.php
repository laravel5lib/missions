<?php

namespace App\Jobs\Reports\Reservations;

use App\Jobs\Job;
use App\Models\v1\Reservation;
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
        $reservations = $reservation->filter($this->request)
            ->with('rooms.type', 'rooms.plans', 'rooms.accommodations', 'costs',
                'trip.group', 'trip.campaign', 'user')
            ->get();

        $data = $this->columnize($reservations);

        dd($data);

        $filename = 'reservations_rooming_' . time();

        (new CSVReport($data, $this->user))->make($filename)->notify();
    }

    public function columnize($reservations)
    {
        return $reservations->map(function($reservation) {
            $data = [
                'Campaign' => $reservation->trip->campaign->name,
                'Surname' => $reservation->surname,
                'Given Names' => $reservation->given_names,
                'Group' => $reservation->trip->group->name,
                'Rooming Cost(s)' => implode(", ", $reservation->costs()->type('optional')->get()->map(function($cost) {
                    return $cost->name . ' ($'.number_format($cost->amountInDollars(),2).')';
                })->all()),
                'Rooms' => implode(',', $reservation->rooms->pluck('type.name')->all()),
                'Plans' => implode(',', $reservation->rooms->pluck('plans')->flatten()->pluck('name')->all())
            ];

            return $data;
        })->all();
    }
}
