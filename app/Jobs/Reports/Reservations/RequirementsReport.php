<?php

namespace App\Jobs\Reports\Reservations;

use App\Jobs\Job;
use App\Models\v1\Reservation;
use App\Services\Reports\CSVReport;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RequirementsReport extends Job implements ShouldQueue
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
        $reservations = $reservation->filter($this->request)
                             ->with('requirements.requirement', 'costs',
                                'trip.group', 'trip.campaign', 'trip.rep',
                                'rep', 'user')
                             ->get();

        $data = $this->columnize($reservations);

        $filename = 'reservations_requirements_' . time();

        (new CSVReport($data, $this->user))->make($filename)->notify();
    }

    private function columnize($reservations)
    {
        $requirementColumns = $reservations->pluck('requirements')
             ->flatten()
             ->keyBy('requirement.name')
             ->map(function($data) {
                return null;
            })->all();

        return $reservations->map(function($reservation) use($requirementColumns) {
            $data = [
                'Given Names' => $reservation->given_names,
                'Surname' => $reservation->surname,
                'Email' => $reservation->email,
                'Trip Rep' => $reservation->rep ? $reservation->rep->name : ($reservation->trip->rep ? $reservation->trip->rep->name : null),
                'Managing User' => $reservation->user->name,
                'Group' => $reservation->trip->group->name,
                'Trip Type' => $reservation->trip->type,
                'Campaign' => $reservation->trip->campaign->name,
                'Role' => teamRole($reservation->desired_role),
                'Rooming Costs' => implode(", ", $reservation->costs()->type('optional')->get()->map(function($cost) {
                    return $cost->name . ' ($'.number_format($cost->amountInDollars(),2).')';
                })->all()),
                'Percent Funded' => $reservation->getPercentRaised()
            ];

            $data = collect($data)->merge($requirementColumns)->all();

            foreach($reservation->requirements as $req)
            {
                $data[$req->requirement->name] = $req->status;
            }

            return $data;

        })->all();
    }
}
