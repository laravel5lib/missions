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
        factory(App\Models\v1\Campaign::class, '1n1d2017')->create()->each(function($campaign) {
            $campaign->slug()->save(factory(App\Models\v1\Slug::class)->make([
                'url' => '1n1d2017'
            ]));
        });
        
        factory(App\Models\v1\Campaign::class, 'india')->create()->each(function($campaign) {
            $campaign->slug()->save(factory(App\Models\v1\Slug::class)->make([
                'url' => 'orphans2angels'
            ]));
        });
    }
}
