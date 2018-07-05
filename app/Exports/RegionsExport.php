<?php

namespace App\Exports;

use App\Models\v1\Region;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use Maatwebsite\Excel\Concerns\FromQuery;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RegionsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, ShouldQueue
{
    use Exportable;

    public function query()
    {
        $query = QueryBuilder::for(Region::class)
            ->allowedFilters([
                'name',
                Filter::exact('campaign_id')
            ])
            ->allowedIncludes(['campaign'])
            ->withCount(['squads', 'members']);

        return $query;
    }

    public function headings(): array
    {
        return [
            'Name',
            'Squads',
            'Missionaries'
        ];
    }

    public function map($region): array
    {
        return [
            $region->name,
            $region->squads_count,
            $region->members_count
        ];
    }
}