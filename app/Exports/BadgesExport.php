<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\v1\SquadMember;
use Maatwebsite\Excel\Concerns\FromQuery;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BadgesExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, ShouldQueue
{
    use Exportable;

    protected $campaignId;

    public function __construct($campaignId)
    {
        $this->campaignId = $campaignId;
    }

    public function query()
    {
        return SquadMember::campaign($this->campaignId)
            ->with([
                'squad', 
                'reservation.flightItinerary.flights.flightSegment', 
                'reservation.trip.group'
            ]);
    }

    public function headings(): array
    {
        return [
            'First Name',
            'Last Name',
            'Role',
            'Organization',
            'Squad',
            'Group',
            'Record Locator',
            'Airline',
            'Flight No.',
            'Departure City',
            'Departure Time',
            'Arrival City'
        ];
    }

    public function map($member): array
    {
        if ($member->reservation) {

            $departureFlight = $this->getDepatureFlight($member);
            $arrivalFlight = $this->getArrivalFlight($member);

            return [
                $member->reservation->given_names,
                $member->reservation->surname,
                teamRole($member->reservation->desired_role),
                $member->reservation->trip->group->name,
                $member->squad->callsign,
                $member->group,
                optional($member->reservation->flightItinerary)->record_locator,
                $departureFlight ? airline($departureFlight->flight_no)->name : null,
                $departureFlight ? $departureFlight->flight_no : null,
                $departureFlight ? $departureFlight->iata_code : null,
                $departureFlight ? Carbon::parse($departureFlight->time)->format('h:i a') : null,
                $arrivalFlight ? $arrivalFlight->iata_code : null
            ];

        }
    }

    private function getDepatureFlight($member)
    {
        if ($member->reservation->flightItinerary) {
            return $member->reservation->flightItinerary->flights()->whereHas('flightSegment', function ($segment) {
                return $segment->where('name', 'Return Departure');
            })->first();
        }
    }

    private function getArrivalFlight($member)
    {
        if ($member->reservation->flightItinerary) {
            $flight = $member->reservation->flightItinerary->flights()->whereHas('flightSegment', function ($segment) {
                return $segment->where('name', 'Return Connection 1');
            })->first();

            if (! $flight) {
                $flight = $member->reservation->flightItinerary->flights()->whereHas('flightSegment', function ($segment) {
                    return $segment->where('name', 'Return Arrival');
                })->first();
            }

            return $flight;
        }
    }
}