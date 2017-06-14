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
        factory(App\Models\v1\RoomType::class)->create([
            'name' => 'double',
            'rules' => [ 'occupancy_limit' => 2, 'same_gender' => true, 'married_only' => false]
        ]);

        factory(App\Models\v1\RoomType::class)->create([
            'name' => 'double_(married)',
            'rules' => [ 'occupancy_limit' => 2, 'same_gender' => false, 'married_only' => true]
        ]);

        factory(App\Models\v1\RoomType::class)->create([
            'name' => 'standard',
            'rules' => [ 'occupancy_limit' => 4, 'same_gender' => true, 'married_only' => false]
        ]);

        factory(App\Models\v1\RoomType::class)->create([
            'name' => 'triple',
            'rules' => [ 'occupancy_limit' => 3, 'same_gender' => true, 'married_only' => false]
        ]);
    }
}
