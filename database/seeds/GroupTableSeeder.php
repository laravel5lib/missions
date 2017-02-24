<?php

use Illuminate\Database\Seeder;

class GroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\v1\Group::class, config('seeders.groups'))->create()->each(function($g) {
            $g->slug()->save(factory(App\Models\v1\Slug::class)->make(['url' => generate_slug($g->name)]));
            $g->managers()->attach(factory(App\Models\v1\Manager::class)->make());
            $g->stories()->saveMany(factory(App\Models\v1\Story::class, 3)->make());
            $g->notes()->saveMany(factory(App\Models\v1\Note::class, 5)->make([
                    'noteable_type' => 'notes',
                    'noteable_id' => $g->id
                ]));
        });
    }
}
