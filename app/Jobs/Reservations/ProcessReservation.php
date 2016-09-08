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
    /**
     * @var Request
     */
    private $request;

    /**
     * Create a new job instance.
     *
     * @param Reservation $reservation
     * @param Request $request
     */
    public function __construct(Reservation $reservation, Request $request)
    {
        $this->reservation = $reservation;
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->request->has('costs')) {
            $this->reservation->syncCosts($this->request->get('costs'));
        } else {
            $active = $this->reservation->trip->activeCosts()->get();

            $maxDate = $active->where('type', 'incremental')->max('active_at');

            $costs = $active->reject(function ($value) use ($maxDate)
            {
                return $value->type == 'incremental' && $value->active_at < $maxDate;
            });

            $this->reservation->syncCosts($costs);
        }

        if ($this->request->has('tags'))
            $this->reservation->tag($this->request->get('tags'));

        $this->reservation->syncRequirements($this->reservation->trip->requirements);
        $this->reservation->syncDeadlines($this->reservation->trip->deadlines);
        $this->reservation->addTodos($this->reservation->trip->todos);

        event(new ReservationWasProcessed($this->reservation));
    }
}
