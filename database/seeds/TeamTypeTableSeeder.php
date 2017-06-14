<?php

use Illuminate\Database\Seeder;

class TeamTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\v1\TeamType::class, 'ministry')->create();
        factory(App\Models\v1\TeamType::class, 'medical')->create();
        factory(App\Models\v1\TeamType::class, 'leadership')->create();
    }
}
