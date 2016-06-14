<?php

use Illuminate\Database\Seeder;

class PassportTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\v1\Passport::class, config('seeders.passports'))->create();
    }
}
