<?php

namespace App\Jobs\Transactions;

use App\Jobs\Job;
use App\Models\v1\Transaction;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendReceiptEmail extends Job implements ShouldQueue
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
     */
    public function handle(Mailer $mailer)
    {
        $donation = $this->donation;

        if($donation->donor->email) {
            $mailer->send('emails.donations.receipt', [
                'donation' => $donation
            ], function ($m) use ($donation) {
                $m->to($donation->donor->email, $donation->donor->name)
                    ->subject('Thanks for the donation!');
            });
        }
    }
}
