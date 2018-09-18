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
        return TripInterest::buildQuery()->with(['trip.campaign', 'trip.group', 'trip.tags']);
    }

    public function headings(): array
    {
        return [
            'Name',
            'Group',
            'Campaign',
            'Trip',
            'Tags',
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
            implode(', ', $interest->trip->tags->pluck('name')->toArray()),
            $interest->status,
            $interest->email,
            $interest->phone,
            implode(', ', $interest->communication_preferences),
            $interest->created_at->toDateTimeString()
        ];
    }
}