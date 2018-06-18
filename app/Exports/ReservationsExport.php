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
        return QueryBuilder::for(Reservation::class)
                ->allowedFilters([
                    'surname', 'given_names', 'email', 'phone_one', 'phone_two',
                    'address', 'city', 'zip', 'state', 'trip_id',
                    Filter::exact('gender'),
                    Filter::exact('status'),
                    Filter::exact('shirt_size'),
                    Filter::exact('desired_role'),
                    Filter::exact('country_code'),
                    Filter::scope('group'),
                    Filter::scope('trip_type'),
                    Filter::scope('campaign'),
                    Filter::scope('has_flight'),
                    Filter::scope('passport_number'),
                    Filter::scope('cost'),
                    Filter::scope('age_range'),
                    Filter::scope('percent_raised_range'),
                    Filter::scope('registered_between'),
                    Filter::scope('dropped'),
                    Filter::scope('dropped_between'),
                    Filter::scope('deposited'),
                    Filter::scope('in_process'),
                    Filter::scope('funded'),
                    Filter::scope('ready'),
                    Filter::scope('funnel')
                ])
                ->allowedIncludes(['trip.group', 'passport', 'requirements'])
                ->with(['trip.group', 'priceables.cost']);
    }

    public function headings(): array
    {
        return [
            'Surname',
            'Given Names',
            'Group',
            'Trip',
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