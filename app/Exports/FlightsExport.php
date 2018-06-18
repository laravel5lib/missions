<?php

namespace App\Exports;

use App\Models\v1\Flight;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use Maatwebsite\Excel\Concerns\FromQuery;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class FlightsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, ShouldQueue
{
    use Exportable;

    public function query()
    {
        return QueryBuilder::for(Flight::class)
            ->allowedFilters([
                'flight_no',
                'iata_code',
                Filter::scope('segment'),
                Filter::scope('itinerary'),
                Filter::scope('record_locator')
            ])
            ->allowedIncludes(['flight-itinerary', 'flight-segment']);
    }

    public function headings(): array
    {
        return [
            'Flight Segment',
            'Flight No.',
            'Airline',
            'City',
            'Airport',
            'Date',
            'Local Time',
            'Record Locator',
            'Passengers'
        ];
    }

    public function map($flight): array
    {
        return [
            $flight->flightSegment->name,
            $flight->flight_no,
            airline($flight->flight_no)['name'],
            $flight->iata_code,
            airport($flight->iata_code)['name'],
            $flight->date->toDateString(),
            $flight->time,
            $flight->flightItinerary->record_locator,
            $flight->flightItinerary->reservations_count
        ];
    }
}