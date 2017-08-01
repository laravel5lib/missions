<?php

namespace App\Jobs\Reports\Transports;

use App\CampaignTransport;
use App\Jobs\Job;
use App\Services\Reports\CSVReport;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PassengersByTransport extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $request;
    protected $user;

    /**
     * Create a new job instance.
     *
     * @param $request
     * @param $user
     */
    public function __construct($request, $user)
    {
        $this->request = $request;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @param CampaignTransport $transport
     * @return void
     */
    public function handle(CampaignTransport $transport)
    {
        $passengers = $transport->filter(array_filter($this->request))
            ->whereNotNull('designation')
            ->with([
                'passengers.reservation.designation',
                'passengers.campaignTransport.departureHub',
                'passengers.campaignTransport.arrivalHub',
                'passengers.reservation.requirements' => function ($query) {
                    $query->where('document_type', 'passports');
                }
            ])
            ->get()
            ->pluck('passengers')
            ->flatten();

        $data = $this->columnize($passengers);

        $filename = 'passengers_by_transport_' . time();

        (new CSVReport($data, $this->user))->make($filename)->notify();
    }

    public function columnize($passengers)
    {
        return $passengers->map(function ($passenger) {
            $reservation = [
                'Given Names' => $passenger->reservation->given_names,
                'Surname' => $passenger->reservation->surname,
                'Email' => $passenger->reservation->email,
                'DOB' => $passenger->reservation->birthday->format('Y-m-d'),
                'Designation' => $passenger->reservation->designation ?
                    implode('', array_flatten($passenger->reservation->designation->content)) : 'none',
            ];

            $passport = $this->passport($passenger->reservation);

            $transport = [
                'Seat No.' => $passenger->seat_no,
                'Type'  => $passenger->campaignTransport->type,
                'Status' => $passenger->campaignTransport->domestic ? 'Domestic' : 'International',
                'Transport' => $passenger->campaignTransport->name,
                'Call Sign' => strtoupper($passenger->campaignTransport->call_sign),
                'Vessel No.' => strtoupper($passenger->campaignTransport->vessel_no),
                'Departure Date' => $passenger->campaignTransport->depart_at ? $passenger->campaignTransport->depart_at->format('Y-m-d') : null,
                'Departure Time' => $passenger->campaignTransport->depart_at ? $passenger->campaignTransport->depart_at->format('h:i a') : null,
                'Departure Call Sign' => $passenger->campaignTransport->departureHub->call_sign,
                'Departure Location' => $passenger->campaignTransport->departureHub->name,
                'Arrival Date' => $passenger->campaignTransport->arrive_at ? $passenger->campaignTransport->arrive_at->format('Y-m-d') : null,
                'Arrival Time' => $passenger->campaignTransport->arrive_at ? $passenger->campaignTransport->arrive_at->format('H:i a') : null,
                'Arrival Call Sign' => $passenger->campaignTransport->arrivalHub ? $passenger->campaignTransport->arrivalHub->call_sign : null,
                'Arrival Location' => $passenger->campaignTransport->arrivalHub ? $passenger->campaignTransport->arrivalHub->name : null
            ];

            return ($reservation + $passport + $transport);
        })->all();
    }

    private function passport($reservation)
    {
        $requirement = $reservation->requirements
            ->where('document_type', 'passports')
            ->first();

        $requirement->load('document');

        $passport = $requirement ? $requirement->document : null;

        return [
            'Passport Number' => $passport ? $passport->number : null,
            'Citizenship' => $passport ? country($passport->citizenship) : null,
            'Nationality' => $passport ? country($passport->birth_country) : null,
            'Expires' => $passport ? $passport->expires_at->toFormattedDateString() : null
        ];
    }
}
