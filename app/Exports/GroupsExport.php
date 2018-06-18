<?php

namespace App\Exports;

use App\Models\v1\Campaign;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class GroupsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, ShouldQueue
{
    use Exportable;

    public function collection()
    {
        $search = request()->get('search');
        $hasPublishedTrips = request()->get('hasPublishedTrips');
        $status = request()->get('status');
        $campaignId = request()->get('campaign');

        $groups = Campaign::findOrFail($campaignId)
            ->groups()
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'LIKE', "%$search%");
            })
            ->when($hasPublishedTrips, function ($query) use ($campaignId) {
                return $query->whereHas('trips', function ($subQuery) use ($campaignId) {
                    return $subQuery->where('campaign_id', $campaignId)
                        ->public()
                        ->published();
                });
            })
            ->when($status, function ($query) use ($status) {
                return $query->where('campaign_group.status_id', $status);
            })
            ->orderBy('name')
            ->get();

        return $groups;
    }

    public function headings(): array
    {
        return [
            'Organization',
            'Reservations',
            'Trips',
            'Status'
        ];
    }

    public function map($group): array
    {
        return [
            $group->name,
            $group->pivot->reservationsCount(),
            $group->pivot->tripsCount(),
            $group->pivot->status
        ];
    }
}