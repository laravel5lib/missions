<?php

use Illuminate\Database\Seeder;

class VisaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\v1\Visa::class, config('seeders.visas'))->create();
    }
}
