<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\v1\Fundraiser;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FundraiserReminder extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Fundraiser $fundraiser)
    {
        $this->fundraiser = $fundraiser;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Your Friendly Reminder')
                    ->from(['address' => 'mail@missions.me', 'name' => 'Missions.Me'])
                    ->markdown('emails.reminders.fundraiser')
                    ->with(['fundraiser' => $this->fundraiser]);
    }
}
