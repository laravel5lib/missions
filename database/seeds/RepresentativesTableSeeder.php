<?php

use Illuminate\Database\Seeder;
use App\Models\v1\Representative;

class RepresentativesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Representative::class, 10)->create();
    }
}
