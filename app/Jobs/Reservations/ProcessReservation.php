<?php

namespace App\Jobs\Reservations;

use App\Events\ReservationWasProcessed;
use App\Jobs\Job;
use App\Models\v1\Reservation;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessReservation extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    /**
     * @var Reservation
     */
    private $reservation;
    private $costs;
    private $tags;

    /**
     * Create a new job instance.
     * @param Reservation $reservation
     * @param null $costs
     * @param null $tags
     */
    public function __construct(Reservation $reservation, $costs = null, $tags = null)
    {
        $this->reservation = $reservation;
        $this->costs = $costs;
        $this->tags = $tags;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->costs) {
            $this->reservation->syncCosts($this->costs);
        } else {
            $active = $this->reservation->trip->activeCosts()->get();

            $maxDate = $active->where('type', 'incremental')->max('active_at');

            $costs = $active->reject(function ($value) use ($maxDate)
            {
                return $value->type == 'incremental' && $value->active_at < $maxDate;
            });

            $this->reservation->syncCosts($costs);
        }

        if ($this->tags)
            $this->reservation->tag($this->tags);

        $this->reservation->syncRequirements($this->reservation->trip->requirements);
        $this->reservation->syncDeadlines($this->reservation->trip->deadlines);
        $this->reservation->addTodos($this->reservation->trip->todos);

        event(new ReservationWasProcessed($this->reservation));
    }
}
