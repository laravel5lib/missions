<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Services\Promocodes;
use App\Models\v1\Reservation;
use Illuminate\Support\Collection;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddReferral extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $reservation;
    protected $promos;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Reservation $reservation, Collection $promos)
    {
        $this->reservation = $reservation;
        $this->promos = $promos;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Promocodes $promocodes)
    {
        $this->promos->each(function ($promoId) use ($promocodes) {
            $this->reservation->promocodes()->create([
                'promotional_id' => $promoId,
                'code' => $promocodes->output()[0]
            ]);
        });
    }
}
