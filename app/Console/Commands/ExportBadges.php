<?php

namespace App\Console\Commands;

use App\Exports\BadgesExport;
use Illuminate\Console\Command;

class ExportBadges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:badges';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a badges export file and save in storage.';

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
        $campaignId = $this->ask('Please enter the campaign ID:');

        (new BadgesExport($campaignId))->store('export/reports/campaigns/'.$campaignId.'/badges.csv', 's3');
    }
}
