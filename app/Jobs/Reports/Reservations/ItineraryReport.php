<?php

namespace App\Jobs\Reports\Reservations;

use App\Jobs\Job;
use App\Models\v1\Reservation;
use App\Services\Reports\CSVReport;
use App\Services\Transportation;
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
                'Email' => $reservation->email,
                'Type' => $reservation->trip->type,
                'Role' => teamRole($reservation->desired_role),
                'Group' => $reservation->trip->group->name,
                'Designation' => $reservation->designation ?
                    implode('', array_flatten($reservation->designation->content)) : 'none',
            ];

            $data = collect($data)
                ->merge($this->tripDetails($reservation))
                ->all();

            return $data;
        })->all();

    }

    private function tripDetails($reservation)
    {
        $outbound = (new Transportation($reservation))->outbound();
        $return = (new Transportation($reservation))->return();
        $transports = $outbound->merge($return);

        $data = $transports->merge(collect([
            'State' => null,
            'Hotel Name' => null,
            'Hotel Phone' => null,
            'Hotel Address' => null
        ]));

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
