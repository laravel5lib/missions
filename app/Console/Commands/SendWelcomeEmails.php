<?php

namespace App\Console\Commands;

use App\Jobs\SendWelcomeEmail;
use App\Models\v1\User;
use Illuminate\Console\Command;

class SendWelcomeEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send-welcome { emails* : An array of user emails }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send welcome emails to users.';
    /**
     * @var User
     */
    private $user;

    /**
     * Create a new command instance.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        parent::__construct();
        $this->user = $user;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $emails = $this->argument('emails');
        $users =  $this->user->whereIn('email', $emails)->get();

        if( ! count($users)) $this->error('Could not find users with matching emails! Nothing sent.');

        if(count($users)) {
            foreach($users as $user) {
                dispatch(new SendWelcomeEmail($user));

                $this->info('Processed email to ' . $user->email);
            }

            $this->info('Done. All emails processed!');
        }
    }
}
