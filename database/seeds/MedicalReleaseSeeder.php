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
        factory(App\Models\v1\MedicalRelease::class, config('seeders.medical_releases'))
            ->create()
            ->each(function ($m) {
                $m->conditions()->saveMany(factory(App\Models\v1\MedicalCondition::class, 2)->make());
                $m->allergies()->saveMany(factory(App\Models\v1\MedicalAllergy::class, 2)->make());
            });
    }
}
