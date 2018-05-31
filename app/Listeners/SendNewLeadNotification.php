<?php

namespace App\Listeners;

use App\Events\LeadCreated;
use App\Mail\NewOrganizationLead;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendNewLeadNotification implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(LeadCreated $event)
    {
        $lead = $event->lead;

        if ($lead->category_id === 1) {
            Mail::to('coordinators@missions.me')->send(new NewOrganizationLead($lead));
        }

        return false;
    }
}
