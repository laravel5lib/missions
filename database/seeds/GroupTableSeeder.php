<?php

use Illuminate\Database\Seeder;

class GroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\v1\Group::class, config('seeders.groups'))->create()->each(function($g) {
            $g->managers()->save(factory(App\Models\v1\Manager::class)->make());
        });
    }
}
