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
                'Surname' => $reservation->surname,
                'Given Names' => $reservation->given_names,
                'Group' => $reservation->trip->group->name,
                'Campaign' => $reservation->trip->campaign->name,
                'Arrival Designation' => $reservation->designation ?
                    implode('', array_flatten($reservation->designation->content)) : 'none'
            ];

            $data = collect($data)
                ->merge($this->domesticHotelInfo($reservation))
                ->merge($this->internationalTravel($reservation))
                ->all();

            return $data;
        })->all();

    }

    private function domesticHotelInfo($reservation)
    {
        $data = collect([
            'Hotel Check-In Date' => null,
            'Hotel Check-In Time' => null
        ]);

        if (! $reservation->designation) {
            $data['Hotel Check-In Date'] = 'July 21';
            $data['Hotel Check-In Time'] = '4:00 pm';
        } else {
            switch (array_flatten($reservation->designation->content)){
                case 'eastern':
                    $data['Domestic Check-In Date'] = 'July 22';
                    $data['Domestic Check-In Time'] = '10:00 pm';
                    break;
                case 'western':
                    $data['Hotel Check-In Date'] = 'July 21';
                    $data['Hotel Check-In Time'] = '4:00 pm';
                    break;
                case 'international':
                    $data['Hotel Check-In Date'] = 'July 21';
                    $data['Hotel Check-In Time'] = '4:00 pm';
                    break;
                default:
                    $data['Hotel Check-In Date'] = 'July 21';
                    $data['Hotel Check-In Time'] = '4:00 pm';
            }
        }

        return $data;
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
            'Hotel Check-Out Date' => null,
            'Hotel Check-Out Time' => null,
            'Shuttle Departure At' => null,
            'Intl Departure At' => null,
            'Intl Arrival At' => null
        ]);

        $flights = array_values($reservation->transports->filter(function ($transport) {
            return $transport->domestic == false && $transport->type == 'flight';
        })->all());

        $len = count($flights);

        if ($len == 0) return $transports;

        collect($flights)->sortBy('depart_at')
            ->each(function ($transport, $key) use($len, $transports) {
                if ($key == 0 && $transport->departureHub && $transport->departureHub->country_code == 'us') {
                    // we get the first iteration for the departure
                    // and make sure departure is from the US
                    $transports['Hotel Check-Out Date'] = $transport->depart_at->subHours(3)->subMinutes(30)->format('F d');
                    $transports['Hotel Check-Out Time'] = $transport->depart_at->subHours(3)->subMinutes(30)->format('h:i a');
                    $transports['Shuttle Departure At'] = $transport->depart_at->subHours(3)->format('h:i a');
                    $transports['Intl Departure At'] = $transport->depart_at->format('F d, h:i a');
                    $transports['Intl Arrival At'] = $transport->arrive_at->format('F d, h:i a');
                }
            });

        return $transports;
    }
}
