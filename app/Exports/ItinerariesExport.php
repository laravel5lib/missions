<?php

namespace App\Exports;

use Illuminate\Http\Request;
use Spatie\QueryBuilder\Filter;
use App\Models\v1\FlightItinerary;
use Spatie\QueryBuilder\QueryBuilder;
use Maatwebsite\Excel\Concerns\FromQuery;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ItinerariesExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, ShouldQueue
{
    use Exportable;

    protected $campaignId;

    public function __construct(Request $request)
    {
        $this->campaignId = $request->input('campaign');
    }

    public function query()
    {
        $query = FlightItinerary::forCampaign($this->campaignId);
        
        $itineraries = QueryBuilder::for($query)
            ->allowedFilters([
                'record_locator', 
                Filter::exact('published'), 
                Filter::exact('type')
            ])
            ->allowedIncludes('flights.flight-segment')
            ->withCount(['reservations', 'flights']);
        
        return $itineraries;
    }

    public function headings(): array
    {
        return [
            'Record Locator',
            'Type',
            'Flights',
            'Passengers'
        ];
    }

    public function map($itinerary): array
    {
        return [
            $itinerary->record_locator,
            $itinerary->type,
            $itinerary->flights_count,
            $itinerary->reservations_count
        ];
    }
}