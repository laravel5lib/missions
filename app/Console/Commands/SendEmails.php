<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send transactional emails.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $type = $this->choice('What type of email do you want to send?', [
            'Welcome Email',
            'Reservation Confirmation',
            'Donation Receipt'
        ]);

        switch ($type) {
            case 'Welcome Email':
                $emails = $this->ask('Please enter all the user account emails you wish to send emails to.');

                $this->call('email:send-welcome', ['emails' => explode(',', $emails)]);

                break;

            case 'Reservation Confirmation':
                $id = $this->ask('Please enter the reservation ID.');

                if ($this->confirm('Would you like to notify the facilitators? [y/n]')) {
                    $this->call('email:send-reservation-confirmation', ['id' => $id, '--notify' => true]);
                } else {
                    $this->call('email:send-reservation-confirmation', ['id' => $id]);
                }

                break;

            case 'Donation Receipt':
                $id = $this->ask('Please enter the transaction ID of the donation.');

                if ($this->confirm('Would you like to notify the recipient(s)? [y/n]')) {
                    $this->call('email:send-receipt', ['id' => $id, '--notify' => true]);
                } else {
                    $this->call('email:send-receipt', ['id' => $id]);
                }

                break;
        }
    }
}
