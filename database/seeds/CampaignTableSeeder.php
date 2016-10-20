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
        factory(App\Models\v1\Campaign::class, '1n1d2017')->create();
        factory(App\Models\v1\Campaign::class, 'india')->create();
    }
}
