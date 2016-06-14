<?php

use Illuminate\Database\Seeder;

class AccommodationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\v1\Accommodation::class, config('seeders.accommodations'))->create()->each(function($transport) {
            $transport->occupants()->saveMany([
                factory(App\Models\v1\Occupant::class)->make(),
                factory(App\Models\v1\Occupant::class)->make()
            ]);
        });
    }
}
