<?php

namespace App\Jobs\Reports\Reservations;

use App\Jobs\Job;
use App\Models\v1\Itinerary;
use App\Models\v1\Reservation;
use App\Services\Reports\CSVReport;
use App\Services\Transportation;
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
                             ->with(
                                 'requirements.document', 'trip.group',
                                 'trip.campaign', 'trip.rep', 'rep',
                                 'designation', 'squads.team.regions',
                                 'transports.departureHub', 'transports.arrivalHub'
                             )
                             ->get();

        $data = $this->columnize($reservations);

        $filename = 'reservations_travel_' . time();

        (new CSVReport($data, $this->user))->make($filename)->notify();
    }

    /**
     * Organize collection into columns
     *
     * @param $reservations
     * @return mixed
     */
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
                        ->merge($this->internationalTravel($reservation))
                        ->all();

            return $data;

        })->all();
    }

    /**
     * Get passport columns
     *
     * @param $reservation
     * @return \Illuminate\Support\Collection
     */
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

    /**
     * Get itinerary columns
     *
     * @param $reservation
     * @return \Illuminate\Support\Collection
     */
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
            'Departure Transport No.' => null
        ]);

        if (!$itinerary) return $travel;

        $itinerary->activities->each(function($act) use($travel) {
            if ($act->type->name == 'arrival') {
                $travel['Arrival Method'] = $act->transports->first() ? $act->transports->first()->type : null;
                $travel['Arrival Location'] = $act->hubs->first() ? $act->hubs->first()->name : null;
                $travel['Arrival At'] = $act->occurred_at ? $act->occurred_at->format('F d, Y h:i a') : null;
                $travel['Arrival Transportation'] = $act->transports->first() ? $act->transports->first()->name : null;
                $travel['Arrival Transport No.'] = $act->transports->first() ? $act->transports->first()->vessel_no : null;
            }

            if ($act->type->name === 'departure') {
                 $travel['Departure Method'] = $act->transports->first() ? $act->transports->first()->type : null;
                 $travel['Departure Location'] = $act->hubs->first() ? $act->hubs->first()->name : null;
                 $travel['Departure At'] = $act->occurred_at ? $act->occurred_at->format('F d, Y h:i a') : null;
                 $travel['Departure Transportation'] = $act->transports->first() ? $act->transports->first()->name : null;
                 $travel['Departure Transport No.'] = $act->transports->first() ? $act->transports->first()->vessel_no : null;
            }
        });

        return $travel;
    }

    /**
     * Get international travel columns
     *
     * @param $reservation
     * @return \Illuminate\Support\Collection
     */
    private function internationalTravel($reservation)
    {
        $transports = collect([
            'Intl Departure Location' => null,
            'Intl Departure At' => null,
            'Intl Departure Transportation' => null,
            'Intl Departure Transport No.' => null,
            'Intl Return Location' => null,
            'Intl Return At' => null,
            'Intl Return Transportation' => null,
            'Intl Return Transport No.' => null,
        ]);

        $flights = array_values($reservation->transports()->orderBy('depart_at', 'asc')->get()->filter(function ($transport) {
            return $transport->domestic == false && $transport->departureHub && $transport->arrivalHub;
        })->all());

        collect($flights)
            ->filter(function ($transport) {
                return $transport->designation == 'outbound';
            })
            ->each(function ($transport) use($transports)
            {
                $transports['Intl Departure Location'] = $transport->departureHub->name;
                $transports['Intl Departure At'] = $transport->depart_at->toDateTimeString();
                $transports['Intl Departure Transportation'] = $transport->name;
                $transports['Intl Departure Transport No.'] = $transport->vessel_no;
            });

        collect($flights)
            ->filter(function ($transport) {
                return $transport->designation == 'return';
            })
            ->each(function ($transport) use($transports)
            {
                $transports['Intl Return Location'] = $transport->departureHub->name;
                $transports['Intl Return At'] = $transport->depart_at->toDateTimeString();
                $transports['Intl Return Transportation'] = $transport->name;
                $transports['Intl Return Transport No.'] = $transport->vessel_no;
            });

        return $transports;
    }
}
