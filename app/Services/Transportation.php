<?php
namespace App\Services;

use App\Models\v1\Reservation;

class Transportation
{

    private $reservation;
    private $transports;

    /**
     * Transportation constructor.
     * @param Reservation $reservation
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
        $this->transports = $this->getTransports();
    }

    public function outbound()
    {
        $data = collect([
            'Hotel Dep' => null,
            'Dep Board' => null,
            'Dep Flight' => null,
            'Dep Time' => null,
            'Dep AM' => null,
            'Dep Port' => null,
            'Dep Arr Time' => null,
            'Dep Arr AM' => null,
        ]);

        collect($this->transports)->filter(function ($transport) {
            return $transport->designation == 'outbound';
        })->each(function ($transport) use ($data) {
            $data['Hotel Dep'] = $transport->depart_at->subHours(3)->format('h:i a');
            $data['Dep Board'] = $transport->depart_at->subHours(1)->format('h:i a');
            $data['Dep Flight'] = strtoupper($transport->vessel_no);
            $data['Dep Time'] = $transport->depart_at->format('h:i a');
            $data['Dep AM'] = strtoupper($transport->arrive_at->format('a'));
            $data['Dep Port'] = strtoupper($transport->departureHub->call_sign);
            $data['Dep Arr Time'] = $transport->arrive_at->format('h:i a');
            $data['Dep Arr AM'] = strtoupper($transport->arrive_at->format('a'));
        });

        return $data;
    }

    public function return()
    {
        $data = collect([
            'Ret Board' => null,
            'Ret Flt' => null,
            'Ret Time' => null,
            'Ret AM' => null,
            'Ret Port' => null,
            'Ret Arr Time' => null,
            'Ret Arr AM' => null
        ]);

        collect($this->transports)->filter(function ($transport) {
            return $transport->designation == 'return';
        })->each(function ($transport) use ($data) {
            $data['Ret Board'] = $transport->depart_at->subHours(1)->format('h:i a');
            $data['Ret Flt'] = strtoupper($transport->vessel_no);
            $data['Ret Time'] = $transport->depart_at->format('h:i a');
            $data['Ret AM'] = strtoupper($transport->arrive_at->format('a'));
            $data['Ret Port'] = strtoupper($transport->departureHub->call_sign);
            $data['Ret Arr Time'] = $transport->arrive_at->format('h:i a');
            $data['Ret Arr AM'] = strtoupper($transport->arrive_at->format('a'));
        });

        return $data;
    }

    private function getTransports()
    {
        return array_values($this->reservation->transports()->orderBy('depart_at', 'asc')->get()->filter(function ($transport) {
            return $transport->domestic == false && $transport->departureHub && $transport->arrivalHub;
        })->all());
    }
}
