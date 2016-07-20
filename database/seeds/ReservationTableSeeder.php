<?php

use Illuminate\Database\Seeder;

class ReservationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\v1\Reservation::class, config('seeders.reservations'))->create()->each(function($r) {

            foreach($r->trip->deadlines as $deadline)
            {
                $r->deadlines()->attach($deadline->id, ['grace_period' => $deadline->grace_period + random_int(0, 3)]);
            }

            foreach($r->trip->requirements as $requirement)
            {
                $statuses = ['incomplete', 'reviewing', 'complete'];
                $rand_key = array_rand($statuses);

                $statuses[$rand_key] == 'complete' ? $completed_at = \Carbon\Carbon::now() : $completed_at = null;

                $r->requirements()->attach($requirement->id, [
                    'grace_period' => $requirement->grace_period + random_int(0, 3),
                    'status' => $statuses[$rand_key],
                    'completed_at' => $completed_at
                ]);
            }

            foreach($r->trip->costs as $cost)
            {
                $r->costs()->attach($cost->id, ['grace_period' => $cost->grace_period + random_int(0, 3)]);
            }

            foreach($r->trip->todos as $todo)
            {
                $r->todos()->save(factory(App\Models\v1\Todo::class)->make([
                    'task' => $todo
                ]));
            }

            $r->companions()->save(factory(App\Models\v1\Companion::class)->make());

            $r->notes()->save(factory(App\Models\v1\Note::class)->make());

            $r->tags()->saveMany([
                factory(App\Models\v1\Tag::class)->make(),
                factory(App\Models\v1\Tag::class)->make()
            ]);
        });
    }
}
