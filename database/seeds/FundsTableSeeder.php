<?php

use Illuminate\Database\Seeder;

class FundsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\v1\Fund::class)->create([
            'name' => 'Missions.Me',
            'balance' => 0,
            'slug' => 'general',
            'class' => 'General',
            'item' => 'General Donation',
            'fundable_type' => 'groups',
            'fundable_id' => function () {
                return factory(App\Models\v1\Group::class)->create([
                    'name' => 'Missions.Me'
                ])->id;
            }
        ]);
    }
}
