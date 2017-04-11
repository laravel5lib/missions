<?php

namespace App\Console\Commands;

use App\Jobs\ApplyPromoCode;
use App\Models\v1\Reservation;
use Illuminate\Console\Command;

class UsePromoCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'promo:use-code 
                            {code : the promo code}
                            {reservation : the ID of the reservation using the code}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Use a promo code.';

    protected $reservation;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Reservation $reservation)
    {
        parent::__construct();
        $this->reservation = $reservation;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $reservation = $this->reservation
            ->findOrFail($this->argument('reservation'));

        dispatch(new ApplyPromoCode($reservation, $this->argument('code')));

        $this->info('Promotional code applied!');
    }
}
