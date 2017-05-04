<?php

use Illuminate\Database\Seeder;

class RoomTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\v1\Room::class)->create([
            'name' => 'double',
            'rules' => [ 'occupancy_limit' => 2]
        ]);

        factory(App\Models\v1\Room::class)->create([
            'name' => 'single',
            'rules' => [ 'occupancy_limit' => 4]
        ]);

        factory(App\Models\v1\Room::class)->create([
            'name' => 'triple',
            'rules' => [ 'occupancy_limit' => 3]
        ]);
    }
}
