<?php

namespace App\Exports;

use App\Models\v1\Reservation;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use Maatwebsite\Excel\Concerns\FromQuery;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReservationsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, ShouldQueue
{
    use Exportable;

    public function query()
    {
        return Reservation::getQuery()->with(['trip.group', 'trip.tags', 'trip.campaign']);
    }

    public function headings(): array
    {
        return [
            'Surname',
            'Given Names',
            'Group',
            'Trip',
            'Tags',
            'Role',
            'Age',
            'Birthday',
            'Gender',
            'Status',
            'Email',
            'Primary Phone',
            'Secondary Phone',
            'Registered',
            '% Raised',
            '$ Raised',
            'Current Rate',
            'Goal',
            'Shirt Size',
            'Country',
            'Zip Code',
            'State/Providence',
            'City',
            'Address'
        ];
    }

    public function map($reservation): array
    {
        return [
            $reservation->surname,
            $reservation->given_names,
            $reservation->trip->group->name,
            $reservation->trip->type,
            implode(', ', $reservation->trip->tags->pluck('name')->toArray()),
            teamRole($reservation->desired_role),
            $reservation->age,
            $reservation->birthday->toDateString(),
            $reservation->gender,
            $reservation->status,
            $reservation->email,
            $reservation->phone_one,
            $reservation->phone_two,
            $reservation->created_at,
            $reservation->getPercentRaised(),
            $reservation->totalRaisedInDollars(),
            $reservation->getCurrentRate() ? $reservation->getCurrentRate()->cost->name : 'N/A',
            $reservation->totalCostInDollars(),
            strtoupper($reservation->shirt_size),
            country($reservation->country_code),
            $reservation->zip,
            $reservation->state,
            $reservation->city,
            $reservation->address
        ];
    }
}