<?php

namespace App\Console\Commands;

use App\Models\v1\Group;
use Illuminate\Console\Command;
use App\Jobs\MigratePendingGroup;

class MigratePendingGroups extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:pending-groups';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrates pending groups to leads.';

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
        Group::whereStatus('pending')->with('notes')->get()->each(function ($group) {
            MigratePendingGroup::dispatch($group);
        });
    }
}
