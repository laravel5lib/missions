<?php

use Illuminate\Database\Seeder;
use App\Models\v1\Represenative;

class RepresenativesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Represenative::class, 10)->create();
    }
}
