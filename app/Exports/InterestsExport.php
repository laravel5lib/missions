<?php

namespace App\Exports;

use App\Models\v1\TripInterest;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use Maatwebsite\Excel\Concerns\FromQuery;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class InterestsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, ShouldQueue
{
    use Exportable;

    public function query()
    {
        return $interests = QueryBuilder::for(TripInterest::class)
            ->allowedFilters([
                'name', 'status', 'email',
                Filter::exact('trip_id'),
                Filter::scope('campaign'),
                Filter::scope('trip_type'),
                Filter::scope('group'),
                Filter::scope('received_between'),
                Filter::scope('incomplete_task'),
                Filter::scope('complete_task')
            ])
            ->allowedIncludes(['trip.campaign', 'trip.group']);
    }

    public function headings(): array
    {
        return [
            'Name',
            'Group',
            'Campaign',
            'Trip',
            'Status',
            'Email',
            'Phone',
            'Preference',
            'Received'
        ];
    }

    public function map($interest): array
    {
        return [
            $interest->name,
            $interest->trip->group->name,
            $interest->trip->campaign->name,
            $interest->trip->type,
            $interest->status,
            $interest->email,
            $interest->phone,
            implode(', ', $interest->communication_preferences),
            $interest->created_at->toDateTimeString()
        ];
    }
}