<?php

namespace App\Jobs;

use App\Models\v1\Referral;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendReferralRequestEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    /**
     * @var Referral
     */
    public $referral;

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

        $mailer->send(
            'emails.referrals.request', 
            ['referral' => $referral], 
            function ($message) use ($referral) {
                $message
                    ->to($referral->recipient_email, $referral->referral_name)
                    ->subject("Your recommendation is needed.");
            });

        $referral->sent_at = now();
        $referral->save();
    }
}
