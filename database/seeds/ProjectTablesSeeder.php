<?php

use Illuminate\Database\Seeder;

class ProjectTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\v1\ProjectType::class, 8)->create();
        factory(App\Models\v1\Cause::class, 5)->create()->each(function($cause) {
//            $cause->initiatives()->saveMany([
//                factory(App\Models\v1\ProjectInitiative::class)->make(),
//                factory(App\Models\v1\ProjectInitiative::class)->make(),
//                factory(App\Models\v1\ProjectInitiative::class)->make()
//            ]);
        });
    }
}
