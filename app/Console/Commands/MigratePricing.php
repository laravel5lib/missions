<?php

namespace App\Console\Commands;

use App\Models\v1\Trip;
use Illuminate\Console\Command;
use App\Jobs\MigratePricingSchema;

class MigratePricing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:pricing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate pricing.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Trip::all()->each(function ($trip) {
            MigratePricingSchema::dispatch($trip);
        });
    }
}
