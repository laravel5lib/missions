<?php

namespace App\Jobs\Transactions;

use App\Jobs\Job;
use App\Models\v1\Transaction;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendDonationNotificationEmail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    /**
     * @var Transaction
     */
    private $donation;

    /**
     * Create a new job instance.
     *
     * @param Transaction $donation
     */
    public function __construct(Transaction $donation)
    {
        $this->donation = $donation;
    }

    /**
     * Execute the job.
     *
     * @param Mailer $mailer
     * @return bool
     */
    public function handle(Mailer $mailer)
    {
        $type = $this->donation->fund->fundable_type;

        switch ($type) {
            case 'reservations' :
                $user = $this->donation->fund->fundable->user;

                $mailer->send('emails.donations.notification', [
                    'donation' => $this->donation,
                    'recipient' => $user
                ], function ($m) use ($user) {
                    $m->to($user->email, $user->name);
                    $m->subject('New Donation Received!');
                });

                break;

            case 'trips' :
                $facilitators = $this->donation->fund->fundable->facilitators;

                foreach($facilitators as $facilitator) {
                    $mailer->send('emails.donations.notification', [
                        'donation' => $this->donation,
                        'recipient' => $facilitator
                    ], function ($m) use ($facilitator) {
                        $m->to($facilitator->email, $facilitator->name);
                        $m->subject('New Donation Received!');
                    });
                }

                break;
        }

        return false;
    }
}
