<?php

namespace App\Console\Commands;

use App\Jobs\Transactions\SendDonationNotificationEmail;
use App\Models\v1\Transaction;
use Illuminate\Console\Command;

class SendReceiptEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send-receipt 
                            { id : Transaction ID of the donation. }
                            { --notify : Whether the recipient should be notified. }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a donation receipt to the donor.';
    /**
     * @var Transaction
     */
    private $transaction;

    /**
     * Create a new command instance.
     *
     * @param Transaction $transaction
     */
    public function __construct(Transaction $transaction)
    {
        parent::__construct();
        $this->transaction = $transaction;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $id = $this->argument('id');

        $donation = $this->transaction->type('donation')->find($id);

        if ( ! $donation) $this->error('There is no donation matching that transaction ID. Nothing sent.');

        if ($donation) {
            dispatch(new \App\Jobs\Transactions\SendReceiptEmail($donation));
            $this->info('Done. Receipt email processed.');

            if($this->option('notify')) {
                dispatch(new SendDonationNotificationEmail($donation));
                $this->info('Recipient has been notified.');
            }
        }
    }
}
