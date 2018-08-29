<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\Temporary\MigrateDocuments;
use App\Jobs\Temporary\MigrateRequirementsToRequireables;
use App\Jobs\Temporary\MigrateRequirementConditionsToRules;
use App\Jobs\Temporary\MigrateReservationRequirementsToRequireables;

class MigrateRequirements extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:requirements';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate requirements to use new requireables';

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
        MigrateRequirementsToRequireables::withChain([
            new MigrateRequirementConditionsToRules,
            new MigrateReservationRequirementsToRequireables,
            new MigrateDocuments
        ])->dispatch();
    }
}
