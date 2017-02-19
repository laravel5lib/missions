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
    protected $signature = 'email:send-welcome { id : The user id } { email? : Recipient email address }';

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
        $id = $this->argument('id');

        $user =  $this->user->find($id);

        $email = $this->argument('email') ? $this->argument('email') : $user->email;

        if( ! $user) $this->error('Could not find user with that id! Nothing sent.');

        if($user) {
            dispatch(new SendWelcomeEmail($user, $email));

            $this->info('Processed email to ' . $email);

            $this->info('Done. All email processed!');
        }
    }
}
