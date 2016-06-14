<?php

use Illuminate\Database\Seeder;

class DecisionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\v1\Interaction\Decision::class, config('seeders.decisions'))->create();
    }
}
