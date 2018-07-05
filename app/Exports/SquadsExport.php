<?php

namespace App\Exports;

use App\Models\v1\Squad;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use Maatwebsite\Excel\Concerns\FromQuery;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SquadsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, ShouldQueue
{
    use Exportable;

    public function query()
    {
        $query = QueryBuilder::for(Squad::class)
            ->allowedFilters([
                'callsign',
                Filter::exact('published'),
                Filter::exact('region_id'),
                Filter::scope('campaign_id'),
            ])
            ->allowedIncludes(['region'])
            ->withCount('members');

        return $query;
    }

    public function headings(): array
    {
        return [
            'Callsign',
            'Members',
            'Region',
            'Status'
        ];
    }

    public function map($squad): array
    {
        return [
            $squad->callsign,
            $squad->members_count,
            $squad->region->name,
            $squad->published ? 'Published' : 'Draft'
        ];
    }
}