<?php

use Illuminate\Database\Seeder;
use Silber\Bouncer\BouncerFacade as Bouncer;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        config(['mail.driver' => 'log']);
        config(['queue.default' => 'sync']);

        $this->call(AirlinesTableSeeder::class);
        $this->call(AirportsTableSeeder::class);
        $this->call(TeamTypeTableSeeder::class);
        $this->call(BouncerSeeder::class);
        $this->call(UploadSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(TripTableSeeder::class);
        $this->call(ProjectTablesSeeder::class);
        $this->call(FundsTableSeeder::class);
        $this->call(ActivityTypeSeeder::class);

        config(['mail.driver' => 'smtp']);
        config(['queue.default' => 'sqs']);
    }
}
