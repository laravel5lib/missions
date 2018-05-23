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
            ->with(
                'rooms.type',
                'rooms.plans',
                'priceables',
                'trip.group',
                'trip.campaign'
            )
            ->get();

        $data = $this->columnize($reservations);

        $filename = 'reservations_rooming_' . time();

        (new CSVReport($data, $this->user))->make($filename)->notify();
    }

    public function columnize($reservations)
    {
        return $reservations->map(function ($reservation) {

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
                'Requested Room' => $reservation->requested_room,
                'Rooms' => implode(', ', $reservation->rooms->pluck('type.name')->all()),
                'Plans' => implode(', ', $reservation->rooms->pluck('plans')->flatten()->pluck('name')->all())
            ];

            return $data;
        })->all();
    }
}
