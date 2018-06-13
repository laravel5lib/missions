<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Models\v1\FlightItinerary;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;
use App\Notifications\FlightItineraryPublished;

class NotifyOfPublishedFlights implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $itineraryUuids;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $itineraryUuids)
    {
        $this->itineraryUuids = $itineraryUuids;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->itineraryUuids as $uuid) {
            $itinerary = FlightItinerary::whereUuid($uuid)
                ->with(['flights', 'reservations'])
                ->firstOrFail();

            Notification::send($itinerary->reservations, new FlightItineraryPublished($itinerary));
        }
    }
}
