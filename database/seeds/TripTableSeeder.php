<?php

use Illuminate\Database\Seeder;

class TripTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        factory(App\Models\v1\Trip::class, config('seeders.trips'))->create()->each(function($t) {
            $t->facilitators()->save(factory(App\Models\v1\Facilitator::class)->make());

            $t->costs()->saveMany(factory(App\Models\v1\Cost::class, 3)->make());

            $t->deadlines()->saveMany(factory(App\Models\v1\Deadline::class, 2)->make());

            $t->requirements()->saveMany(factory(App\Models\v1\Requirement::class, 4)->make());

            $t->notes()->save(factory(App\Models\v1\Note::class)->make());

        });
    }
}
