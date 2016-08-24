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

            $r->companions()->save(factory(App\Models\v1\Companion::class)->make());

            $r->notes()->save(factory(App\Models\v1\Note::class)->make());

            $r->tag(['vip', 'missionary']);
        });

        // Give the admin user at least one reservation.
        $user = App\Models\v1\User::where('name', 'Admin')->firstOrFail();

        factory(App\Models\v1\Reservation::class)->create(['user_id' => $user->id])->each(function($r) {

            $r->companions()->save(factory(App\Models\v1\Companion::class)->make());

            $r->notes()->save(factory(App\Models\v1\Note::class)->make());

            $r->tag(['vip', 'missionary']);
        });
    }
}
