<?php

use Illuminate\Database\Seeder;

class TransportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\v1\Transport::class, config('seeders.transports'))->create()->each(function($transport) {
            $transport->passengers()->saveMany([
                factory(App\Models\v1\Passenger::class)->make(),
                factory(App\Models\v1\Passenger::class)->make(),
                factory(App\Models\v1\Passenger::class)->make(),
                factory(App\Models\v1\Passenger::class)->make(),
                factory(App\Models\v1\Passenger::class)->make(),
                factory(App\Models\v1\Passenger::class)->make(),
                factory(App\Models\v1\Passenger::class)->make(),
                factory(App\Models\v1\Passenger::class)->make(),
                factory(App\Models\v1\Passenger::class)->make(),
                factory(App\Models\v1\Passenger::class)->make()
            ]);
        });
    }
}
