<?php

use App\Models\v1\Cost;
use App\Models\v1\User;
use App\Models\v1\Payment;
use Illuminate\Database\Seeder;

class ProjectTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cause = (new AngelHouseCauseWithInitiatives)->create();
        (new ActiveProject)->times(20)->create(['cause_id' => $cause['id']]);

        $cause = (new CleanWaterCauseWithInitiatives)->create();
        (new ActiveProject)->times(10)->create(['cause_id' => $cause['id']]);
    }
}
