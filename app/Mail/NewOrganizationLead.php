<?php

namespace App\Mail;

use App\Models\v1\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewOrganizationLead extends Mailable
{
    use Queueable, SerializesModels;

    protected $lead;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Lead $lead)
    {
        $this->lead = $lead;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('New Organization Lead')
            ->from(['address' => 'mail@missions.me', 'name' => 'Missions.Me'])
            ->markdown('emails.leads.organization')
            ->with(['lead' => $this->lead]);
    }
}
