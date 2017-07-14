<?php

namespace App\Jobs\Reports\Reservations;

use App\Jobs\Job;
use App\Models\v1\Reservation;
use App\Models\v1\ReservationRequirement;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MedicalInfo extends Job implements ShouldQueue
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
     * @param Reservation $reservation
     * @param ReservationRequirement $requirement
     * @return void
     */
    public function handle(Reservation $reservation, ReservationRequirement $requirement)
    {
        $reservationIds = $reservation->filter(array_filter($this->request))->pluck('id');

        $requirements = $requirement->where('document_type', '=', 'medical_releases')
            ->whereIn('reservation_id', $reservationIds)
            ->with('document.conditions', 'document.allergies', 'reservation')
            ->get();

        $data = $this->columnize($requirements);

        $filename = 'reservations_basic_' . time();

        (new CSVReport($data, $this->user))->make($filename)->notify();
    }

    private function columnize($requirements)
    {
        return $requirements->map(function ($requirement)
        {
            $height = convert_to_inches($requirement->reservation->height);
            $weight = convert_to_pounds($requirement->reservation->weight);

            $conditions = $requirement->document ? $requirement->document->conditions : [];
            $allergies = $requirement->document ? $requirement->document->allergies : [];
            $emergency = $requirement->document ? $requirement->document->emergency_contact : [];

            return [
                'Surname' => $requirement->reservation->surname,
                'Given Names' => $requirement->reservation->given_names,
                'Gender' => $requirement->reservation->gender,
                'Age' => $requirement->reservation->age,
                'Birthday' => $requirement->reservation->birthday->format('M d, Y'),
                'Height' => $height['ft'].'\' '.$height['in'].'"',
                'Weight' => $weight.' lb',
                'Conditions' => $conditions,
                'Allergies' => $allergies,
                'Emergency Contact' => $emergency['name'],
                'Emergency Phone' => $emergency['phone']
            ];
        });
    }
}
