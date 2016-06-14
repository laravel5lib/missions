<?php

use Illuminate\Database\Seeder;

class DonationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\v1\Donation::class, 100)->create();
    }
}
