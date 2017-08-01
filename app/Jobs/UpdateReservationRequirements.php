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

        if (! $resReqs->count()) {
            $reservations = $tripReq->requester->reservations;

            if ($tripReq->conditions->count()) {
                $reservations->reject(function ($res) use ($tripReq) {

                    $matches = collect([]);

                    $tripReq->conditions->each(function ($condition) use ($res, $matches) {
                        if ($condition->type === 'role') {
                            $matches->push(in_array($res->desired_role, $condition->applies_to));
                        } elseif ($condition->type === 'age') {
                            $matches->push($res->age < (int) $condition->applies_to[0]);
                        } else {
                            $matches->push(false);
                        }
                    });

                    return in_array(false, $matches->all());
                })->each(function ($reservation) use ($reservationRequirement, $tripReq) {
                    $reservationRequirement->create([
                        'reservation_id' => $reservation->id,
                        'requirement_id' => $tripReq->id,
                        'grace_period' => $tripReq->grace_period,
                        'document_id' => $tripReq->document_id,
                        'document_type' => $tripReq->document_type,
                        'status' => 'incomplete'
                    ]);
                });
            } else {
                $reservations->each(function ($reservation) use ($reservationRequirement, $tripReq) {
                    $reservationRequirement->create([
                        'reservation_id' => $reservation->id,
                        'requirement_id' => $tripReq->id,
                        'grace_period' => $tripReq->grace_period,
                        'document_id' => $tripReq->document_id,
                        'document_type' => $tripReq->document_type,
                        'status' => 'incomplete'
                    ]);
                });
            }
        } else {
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
}
