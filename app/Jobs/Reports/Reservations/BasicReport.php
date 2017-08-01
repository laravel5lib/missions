<?php

namespace App\Jobs\Reports\Reservations;

use App\Jobs\Job;
use App\Models\v1\Reservation;
use App\Services\Reports\CSVReport;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BasicReport extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $request;
    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request, $user)
    {
        $this->request = $request;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Reservation $reservation)
    {
        $reservations = $reservation->filter(array_filter($this->request))
                             ->with('user', 'trip.campaign', 'trip.group', 'rep')
                             ->get();

        $data = $this->columnize($reservations);

        $filename = 'reservations_basic_' . time();

        (new CSVReport($data, $this->user))->make($filename)->notify();
    }

    private function columnize($reservations)
    {
        return $reservations->map(function ($reservation) {
            return [
                'given_names' => $reservation->given_names,
                'surname' => $reservation->surname,
                'gender' => $reservation->gender,
                'marital_status' => $reservation->status,
                'shirt_size' => shirtSize($reservation->shirt_size),
                'age' => $reservation->age,
                'birthday' => $reservation->birthday->format('M d, Y'),
                'email' => $reservation->email,
                'primary_phone' => $reservation->phone_one,
                'secondary_phone' => $reservation->phone_two,
                'street_address' => $reservation->address,
                'city' => $reservation->city,
                'state_providence' => $reservation->state,
                'zip_postal' => $reservation->zip,
                'country' => country($reservation->country_code),
                'trip_rep' => $reservation->rep ? $reservation->rep->name : ($reservation->trip->rep ? $reservation->trip->rep->name : null),
                'managing_user' => $reservation->user->name,
                'user_email' => $reservation->user->email,
                'user_primary_phone' => $reservation->user->phone_one,
                'user_secondary_phone' => $reservation->user->secondary_phone,
                'group' => $reservation->trip->group->name,
                'trip_type' => $reservation->trip->type,
                'campaign' => $reservation->trip->campaign->name,
                'country_located' => country($reservation->trip->campaign->country_code),
                'start_date' => $reservation->trip->started_at->format('M d, Y'),
                'end_date' => $reservation->trip->ended_at->format('M d, Y'),
                'desired_role' => teamRole($reservation->desired_role),
                'designation' => $reservation->designation ?
                    implode('', array_flatten($reservation->designation->content)) : 'none',
                'Registered' => $reservation->created_at->timezone('America/Detroit')->format('F d, Y h:i a'),
                'Updated' => $reservation->updated_at->timezone('America/Detroit')->format('F d, Y h:i a')
            ];
        })->all();
    }
}
