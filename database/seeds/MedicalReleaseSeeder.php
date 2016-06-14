<?php

use Illuminate\Database\Seeder;

class MedicalReleaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\v1\Medical\Release::class, config('seeders.medical_releases'))->create();
    }
}
