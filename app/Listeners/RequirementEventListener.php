<?php

namespace App\Listeners;

use App\Jobs\SendRequirementNotificationEmail;

class RequirementEventListener
{

    /**
     * Register for the Trip.
     *
     * @param $event
     */
    public function statusChange($event)
    {
        dispatch(new SendRequirementNotificationEmail($event->requirement->id));
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\RequirementWasUpdated',
            'App\Listeners\RequirementEventListener@statusChange'
        );
    }
}
