<?php

namespace App\Jobs;

use App\Models\v1\Squad;
use Illuminate\Bus\Queueable;
use App\Notifications\SquadPublished;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;

class NotifyOfPublishedSquads implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $squadUuids;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $squadUuids)
    {
        $this->squadUuids = $squadUuids;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->squadUuids as $uuid) {
            $squad = Squad::whereUuid($uuid)
                ->with(['members.reservation', 'region'])
                ->firstOrFail();

            $reservations = $squad->members->pluck('reservation')->flatten();

            Notification::send($reservations, new SquadPublished($squad));
        }
    }
}
