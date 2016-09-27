<?php

use Illuminate\Database\Seeder;

class CampaignTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\v1\Campaign::class, 'active', 3)->create();
        factory(App\Models\v1\Campaign::class, 'archived', 3)->create();
    }
}
