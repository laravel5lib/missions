<?php

namespace App\Jobs\Reports\Reservations;

use App\Jobs\Job;
use App\Models\v1\Reservation;
use App\Services\Reports\CSVReport;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ItineraryReport extends Job implements ShouldQueue
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
        $reservations = $reservation
            ->filter(array_filter($this->request))
            ->with(
                'trip.group', 'trip.campaign', 'designation',
                'transports.departureHub', 'transports.arrivalHub'
            )
            ->get();

        $data = $this->columnize($reservations);

        $filename = 'reservation_itineraries_' . time();

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
                'Last Name' => $reservation->surname,
                'First Name' => getFirstName($reservation->given_names),
                'Type' => $reservation->trip->type,
                'Role' => teamRole($reservation->desired_role),
                'Group' => $reservation->trip->group->name
            ];

            $data = collect($data)
                ->merge($this->tripDetails($reservation))
                ->all();

            return $data;
        })->all();

    }

    private function tripDetails($reservation)
    {
        $designation = implode('', array_flatten($reservation->designation ? $reservation->designation->content : []));

        $data = collect([
            'Hotel Dep' => null,
            'Dep Board' => null,
            'Dep Flight' => null,
            'Dep Time' => null,
            'Dep AM' => null,
            'Dep Port' => null,
            'Dep Arr Time' => null,
            'Dep Arr AM' => null,
            'Ret Board' => null,
            'Ret Flt' => null,
            'Ret Time' => null,
            'Ret AM' => null,
            'Ret Port' => null,
            'Ret Arr Time' => null,
            'Ret Arr AM' => null,
            'State' => null,
            'Hotel Name' => null,
            'Hotel Phone' => null,
            'Hotel Address' => null
        ]);

        // Grab reservation's flights ordered by earliest departure date
        $flights = array_values($reservation->transports()->orderBy('depart_at', 'asc')->get()->filter(function ($transport) {
            return $transport->domestic == false && $transport->type == 'flight';
        })->all());

        $len = count($flights);
        if ($len == 0) return $data;

        collect($flights)->each(function ($flight, $key) use($len, $data, $reservation, $designation) {
            // we get the first departure from the US
            if ($key == 0 && $flight->departureHub && $flight->departureHub->country_code == 'us') {
                $data['Hotel Dep'] = $flight->depart_at->subHours(3)->format('h:i a');
                $data['Dep Board'] = $flight->depart_at->subHours(1)->format('h:i a');
                $data['Dep Flight'] = strtoupper($flight->vessel_no);
                $data['Dep Time'] = $flight->depart_at->format('h:i a');
                $data['Dep AM'] = strtoupper($flight->arrive_at->format('a'));
                $data['Dep Port'] = strtoupper($flight->departureHub->call_sign);
                $data['Dep Arr Time'] = $flight->arrive_at->format('h:i a');
                $data['Dep Arr AM'] = strtoupper($flight->arrive_at->format('a'));
            }

            // we get the last return departing from outside the US and arriving in the US
            if ($key == $len - 1 && $flight->arrivalHub && $flight->arrivalHub->country_code == 'us') {
                // we get the last iteration for the return
                // and make sure arrival is in the US
                $data['Ret Board'] = $flight->depart_at->subHours(1)->format('h:i a');
                $data['Ret Flt'] = strtoupper($flight->vessel_no);
                $data['Ret Time'] = $flight->depart_at->format('h:i a');
                $data['Ret AM'] = strtoupper($flight->arrive_at->format('a'));
                $data['Ret Port'] = strtoupper($flight->departureHub->call_sign);
                $data['Ret Arr Time'] = $flight->arrive_at->format('h:i a');
                $data['Ret Arr AM'] = strtoupper($flight->arrive_at->format('a'));
            }
        });

        $data['State'] = $this->getRegionAssignment($reservation);
        $data['Hotel Name'] = implode(',', $this->getInCountryAccommodations($reservation)->pluck('name')->all());
        $data['Hotel Phone'] = implode(',', $this->getInCountryAccommodations($reservation)->pluck('phone')->all());
        $data['Hotel Address'] = implode(',', $this->getInCountryAccommodations($reservation)->pluck('address')->all());

        return $data;
    }

    private function getRegionAssignment($reservation)
    {
        return implode(',', $reservation->squads
            ->pluck('team')
            ->flatten()
            ->pluck('regions')
            ->flatten()
            ->pluck('name')
            ->all()
        );
    }

    private function getInCountryAccommodations($reservation)
    {
        $accommodations = $reservation->rooms->pluck('accommodations')->flatten()->filter(function ($hotel) {
            return $hotel->country_code != 'us';
        })->all();

        $hotels = [];

        foreach ($accommodations as $hotel) {
            $hotels[$hotel->id] = [
                'name' => $hotel ? ucwords($hotel->name) : null,
                'phone' => $hotel ? $hotel->phone : null,
                'address' => $hotel ? $hotel->address_one.' '.$hotel->address_two.', '.$hotel->city.', '.$hotel->state.' '.$hotel->zip : null
            ];
        }

        return collect($hotels);
    }
}
