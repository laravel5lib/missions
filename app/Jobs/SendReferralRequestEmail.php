<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Models\v1\Referral;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendReferralRequestEmail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    /**
     * @var Referral
     */
    private $referral;

    /**
     * Create a new job instance.
     *
     * @param Referral $referral
     */
    public function __construct(Referral $referral)
    {
        $this->referral = $referral;
    }

    /**
     * Execute the job.
     *
     * @param Mailer $mailer
     */
    public function handle(Mailer $mailer)
    {
        $referral = $this->referral;

        $mailer->send('emails.referrals.request', ['referral' => $referral], function ($m) use ($referral) {
            $m->to($referral->referral_email, $referral->referral_name)
                ->subject('Your referral is needed to send ' . $referral->name . ' on a mission trip.');
        });
    }
}
