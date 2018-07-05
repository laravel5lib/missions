<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\v1\SquadMember;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use Maatwebsite\Excel\Concerns\FromQuery;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SquadMembersExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, ShouldQueue
{
    use Exportable;

    public function query()
    {
        return SquadMember::buildQuery();
    }

    public function headings(): array
    {
        return [
            'Surname',
            'Given Names',
            'Role',
            'Group',
            'Squad',
            'Region',
            'Organization',
            'Trip Type',
            'Gender',
            'Age',
            'Status'
        ];
    }

    public function map($member): array
    {
        return [
            $member->surname,
            $member->given_names,
            teamRole($member->role),
            $member->group,
            $member->callsign,
            $member->region_name,
            $member->organization_name,
            $member->trip_type,
            $member->gender,
            $member->birthday ? Carbon::parse($member->birthday)->diffInYears() : null,
            $member->status
        ];
    }
}