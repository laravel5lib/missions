<?php

use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\v1\Region::class, config('seeders.regions'))->create()->each(function($r) {
            $r->teams()->saveMany([
                factory(App\Models\v1\Team::class)->make(),
                factory(App\Models\v1\Team::class)->make(),
                factory(App\Models\v1\Team::class)->make()
            ]);
        });
    }
}
