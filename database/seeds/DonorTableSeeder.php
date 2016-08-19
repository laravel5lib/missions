<?php

use Illuminate\Database\Seeder;

class DonorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\v1\Donor::class, 25)->create()->each(function($donor) {
            $donor->donations()->saveMany(factory(App\Models\v1\Donation::class, 4)->make());
        });
    }
}
