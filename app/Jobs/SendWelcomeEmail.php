<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Models\v1\User;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendWelcomeEmail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var User
     */
    private $user;

    /**
     * @var email
     */
    private $email;

    /**
     * Create a new job instance.
     *
     * @param User $user
     */
    public function __construct(User $user, $email = null)
    {
        $this->user = $user;
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @param Mailer $mailer
     */
    public function handle(Mailer $mailer)
    {
        $user = $this->user;
        $email = $this->email ? $this->email : $user->email;

        $mailer->send('emails.welcome', ['user' => $user], function ($m) use ($user, $email) {
            $m->to($email, $user->name)->subject('Welcome to Missions.Me!');
        });
    }
}
