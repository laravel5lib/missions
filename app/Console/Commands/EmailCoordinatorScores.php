<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\NotifyCoordinatorsWithScores;

class EmailCoordinatorScores extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send-tc-scores';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send registration commitment scoreboard as email to Team Coordinators.';

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
        NotifyCoordinatorsWithScores::dispatch();
    }
}
