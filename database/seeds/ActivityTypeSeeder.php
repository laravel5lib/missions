<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class ActivityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\v1\ActivityType::class)->create(['name' => 'departure']);
        factory(App\Models\v1\ActivityType::class)->create(['name' => 'arrival']);
        factory(App\Models\v1\ActivityType::class)->create(['name' => 'connection']);
    }
}
