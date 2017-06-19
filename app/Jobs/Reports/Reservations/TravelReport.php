<?php

namespace App\Jobs\Reports\Reservations;

use App\Jobs\Job;
use App\Models\v1\Itinerary;
use App\Models\v1\Reservation;
use App\Services\Reports\CSVReport;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class TravelReport extends Job implements ShouldQueue
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
                             ->with('requirements.document', 'trip.group',
                                'trip.campaign', 'trip.rep', 'rep',
                                'designation', 'squads.team.regions')
                             ->get();

        $data = $this->columnize($reservations);

        $filename = 'reservations_travel_' . time();

        (new CSVReport($data, $this->user))->make($filename)->notify();
    }

    private function columnize($reservations)
    {

        return $reservations->map(function($reservation) {
            $data = [
                'First Name' => $reservation->given_names,
                'Last Name' => $reservation->surname,
                'Arrival Designation' => $reservation->designation ?
                    implode('', array_flatten($reservation->designation->content)) : 'none',
                'Role' => teamRole($reservation->desired_role),
                'Group' => $reservation->trip->group->name,
                'Trip Type' => $reservation->trip->type,
                'Campaign' => $reservation->trip->campaign->name,
                'Marital Status' => $reservation->status,
                'Gender' => $reservation->gender,
                'Age' => $reservation->age,
                'Birthday' => $reservation->birthday->toFormattedDateString(),
                'Weight' => convert_to_pounds($reservation->weight).' lbs',
                'Email' => $reservation->email,
                'Primary Phone' => $reservation->phone_one,
                'Shirt Size' => shirtSize($reservation->shirt_size),
                'Region' => implode(',', $reservation->squads->pluck('team')->flatten()->pluck('regions')->flatten()->pluck('name')->all()),
                'Squad' => implode(',', $reservation->squads->pluck('team')->flatten()->pluck('callsign')->all()),
                'Squad Group' => implode(',', $reservation->squads->pluck('callsign')->all())
            ];

            $data = collect($data)
                        ->merge($this->passport($reservation))
                        ->merge($this->itinerary($reservation))
                        ->all();

            return $data;

        })->all();
    }

    private function passport($reservation)
    {
        $requirement = $reservation->requirements
            ->where('document_type', 'passports')
            ->first();

        $passport = $requirement ? $requirement->document : null;

        return collect([
            // 'Passport Surname' => $passport ? $passport->surname : null,
            // 'Passport Given Names' => $passport ? $passport->given_names : null,
            'Passport Number' => $passport ? $passport->number : null,
            'Citizenship' => $passport ? country($passport->citizenship) : null,
            // 'Nationality' => $passport ? country($passport->birth_country) : null,
            'Expires' => $passport ? $passport->expires_at->toFormattedDateString() : null
        ]);
    }

    private function itinerary($reservation)
    {
        $requirement = $reservation->requirements->where('document_type', 'travel_itineraries')->first();

        $itinerary_id = $requirement ? $requirement['document_id'] : null;

        $itinerary = Itinerary::with('activities.type', 'activities.hubs', 'activities.transports')
                          ->find($itinerary_id);

        $travel = collect([
            'Arrival Method' => null,
            'Arrival Location' => null,
            'Arrival At' => null,
            'Arrival Transportation' => null,
            'Arrival Transport No.' => null,
            'Departure Method' => null,
            'Departure Location' => null,
            'Departure At' => null,
            'Departure Transportation' => null,
            'Departure Transport No.' => null,
            'Intl Departure Location' => null,
            'Intl Departure At' => null,
            'Intl Departure Transportation' => null,
            'Intl Departure Transport No.' => null,
            'Intl Return Location' => null,
            'Intl Return At' => null,
            'Intl Return Transportation' => null,
            'Intl Return Transport No.' => null,
        ]);

        if (!$itinerary) return $travel;

        $itinerary->activities->each(function($act) use($travel) {
            if ($act->type->name == 'arrival') {
                $travel['Arrival Method'] = $act->transports->first() ? $act->transports->first()->type : null;
                $travel['Arrival Location'] = $act->hubs->first() ? $act->hubs->first()->name : null;
                $travel['Arrival At'] = $act->occurred_at ? $act->occurred_at->timezone('America/Detroit')->format('F d, Y h:i a') : null;
                $travel['Arrival Transportation'] = $act->transports->first() ? $act->transports->first()->name : null;
                $travel['Arrival Transport No.'] = $act->transports->first() ? $act->transports->first()->vessel_no : null;
            }

            if ($act->type->name === 'departure') {
                 $travel['Departure Method'] = $act->transports->first() ? $act->transports->first()->type : null;
                 $travel['Departure Location'] = $act->hubs->first() ? $act->hubs->first()->name : null;
                 $travel['Departure At'] = $act->occurred_at ? $act->occurred_at->timezone('America/Detroit')->format('F d, Y h:i a') : null;
                 $travel['Departure Transportation'] = $act->transports->first() ? $act->transports->first()->name : null;
                 $travel['Departure Transport No.'] = $act->transports->first() ? $act->transports->first()->vessel_no : null;
            }
        });

        return $travel;
    }
}
