<?php

namespace App\Mail;

use App\Models\v1\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailVerification extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Verify your Email')
            ->from(['address' => 'mail@missions.me', 'name' => 'Missions.Me'])
            ->markdown('auth.emails.verify')
            ->with([
                'name' => $this->user->name,
                'actionUrl' => route('verify.email.token', ['token' => $this->user->email_token])
            ]);
    }
}
