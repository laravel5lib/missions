<?php

use App\Models\v1\Fundraiser;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class StoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        factory(App\Models\v1\Story::class, 40)->create()->each(function($story) use($faker) {

            $story->publish([
                [
                    'type' => 'fundraisers',
                    'id' => $faker->randomElement(Fundraiser::pluck('id')->toArray()),
                ]
            ]);
        });
    }
}
