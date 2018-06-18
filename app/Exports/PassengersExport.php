<?php

namespace App\Exports;

use App\Models\v1\Reservation;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PassengersExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, ShouldQueue
{
    use Exportable;

    public function collection()
    {
        $segment = request()->input('segment');
        $campaignId = request()->input('campaign');

        $query = Reservation::whereHas('trip', function ($trip) use ($campaignId) {
            return $trip->where('campaign_id', $campaignId);
        })
        ->whereNotNull('flight_itinerary_id')
        ->when($segment, function ($query) use ($segment) {
            $query->whereHas('flightItinerary.flights', function($flight) use ($segment) {
                return $flight->segment($segment);
            })->with(['flightItinerary.flights' => function($flight) use ($segment) {
                $flight->segment($segment);
            }, 'trip.group']);
        });


        $passengers = QueryBuilder::for($query)
            ->allowedFilters([
                'surname', 
                'given_names', 
                Filter::scope('record_locator'),
                Filter::scope('flight_no'),
                Filter::scope('iata_code'),
                Filter::scope('group'),
                Filter::scope('trip_type'),
                Filter::scope('itinerary'),
                Filter::scope('published_itinerary')
            ])
            ->allowedIncludes(['flight-itinerary.flights', 'passport']);

        return $passengers->get();
    }

    public function headings(): array
    {
        return [
            'Flight Segment',
            'Surname',
            'Given Names',
            'Group',
            'Trip',
            'Type',
            'Record Locator',
            'Date',
            'Local Time',
            'Flight No.',
            'Airline',
            'City',
            'Airport',
            'Passport No.',
            'Passport Exp.'
        ];
    }

    public function map($passenger): array
    {
        $passport = optional($passenger->passport()->first())->document;

        $flight = $passenger->flightItinerary->flights()->whereHas('flightSegment', function ($query) {
            return $query->whereUuid(request()->input('segment'));
        })->first();

        return [
            $flight->flightSegment->name,
            $passenger->surname,
            $passenger->given_names,
            $passenger->trip->group->name,
            $passenger->trip->type,
            $passenger->flightItinerary->type,
            $passenger->flightItinerary->record_locator,
            $flight->date->toDateString(),
            $flight->time,
            $flight->flight_no,
            airline($flight->flight_no)['name'],
            $flight->iata_code,
            airport($flight->iata_code)['name'],
            $passport ? $passport->number : '',
            $passport ? $passport->expires_at->toDateString() : ''
        ];
    }
}