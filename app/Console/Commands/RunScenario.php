<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RunScenario extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scenario:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh database migration and seed database with data for the given scenario.';

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
        $this->call('migrate:refresh', [
            '--force' => true,
        ]);

        $this->call('db:seed', [
            '--class' => 'BouncerSeeder',
        ]);

        $this->call('db:seed', [
            '--class' => 'AccountingTablesSeeder',
        ]);

        $this->call('db:seed', [
            '--class' => 'AirportsTableSeeder',
        ]);

        $this->call('db:seed', [
            '--class' => 'AirlinesTableSeeder',
        ]);

        $this->call('db:seed', [
            '--class' => 'UserTableSeeder',
        ]);
    }
}
