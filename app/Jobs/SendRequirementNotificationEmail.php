<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Models\v1\ReservationRequirement;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendRequirementNotificationEmail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var id
     */
    private $id;

    /**
     * Create a new job instance.
     *
     * @param $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @param Mailer $mailer
     * @param ReservationRequirement $reservationRequirement
     * @internal param ReservationRequirement $requirement
     */
    public function handle(Mailer $mailer, ReservationRequirement $reservationRequirement)
    {
        $requirement = $reservationRequirement->findOrFail($this->id);

        // managing user
        $mailer->send('emails.requirements.update', [
            'recipient_name' => $requirement->reservation->user->name,
            'reservation_name' => $requirement->reservation->name,
            'country' => country($requirement->reservation->trip->campaign->country_code),
            'requirement' => $requirement->requirement->name,
            'status' => $requirement->status,
            'gender' => $requirement->reservation->gender,
        ], function ($m) use ($requirement) {
            $m->to($requirement->reservation->user->email, $requirement->reservation->user->name)
                ->subject('The status of '.$requirement->reservation->name.'\'s travel requirement has changed!');
        });

        // facilitator

        // trip rep
    }
}
