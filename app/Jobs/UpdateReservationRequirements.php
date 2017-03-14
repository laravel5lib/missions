<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Models\v1\Requirement;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\v1\ReservationRequirement;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateReservationRequirements extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $requirement;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Requirement $requirement)
    {
        $this->requirement = $requirement;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ReservationRequirement $reservationRequirement)
    {
        $tripReq = $this->requirement;
        $resReqs = $reservationRequirement->where('requirement_id', $tripReq->id)->get();

        if (! $resReqs->count())
            $tripReq->requester->reservations->each(function($reservation) use ($reservationRequirement, $tripReq) {
                $reservationRequirement->create([
                    'reservation_id' => $reservation->id,
                    'requirement_id' => $tripReq->id,
                    'grace_period' => $tripReq->grace_period,
                    'document_id' => $tripReq->document_id,
                    'document_type' => $tripReq->document_type,
                    'status' => 'incomplete',
                ]);
            });

        collect($resReqs)->each(function ($req) use ($tripReq) {
            $req->update([
                'grace_period' => $tripReq->grace_period,
                'document_id' => $tripReq->document_type <> $req->document_type ? null : $req->document_id,
                'document_type' => $tripReq->document_type,
                'status' => $tripReq->document_type <> $req->document_type ? 'incomplete' : $req->status
            ]);
        });

    }
}
