<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\v1\User::class, 'admin')->create()->each(function($user) {
            $user->assign('admin');
            $user->links()->saveMany([
                factory(App\Models\v1\Link::class)->make(['name' => 'facebook', 'url' => 'https://facebook.com']),
                factory(App\Models\v1\Link::class)->make(['name' => 'twitter', 'url' => 'https://twitter.com']),
                factory(App\Models\v1\Link::class)->make()
            ]);
            $user->stories()->saveMany(factory(App\Models\v1\Story::class, 3)->make(['author_id' => $user->id, 'author_type' => 'users']));
            $user->accolades()->save(factory(App\Models\v1\Accolade::class)->make());
            $user->accolades()->save(factory(App\Models\v1\Accolade::class, 'trip_history')->make());
            $user->essays()->save(factory(App\Models\v1\Essay::class)->make());
            $user->passports()->save(factory(App\Models\v1\Passport::class)->make());
            $user->visas()->save(factory(App\Models\v1\Visa::class)->make());
        });
        factory(App\Models\v1\User::class, 'joe')->create();
        factory(App\Models\v1\User::class, config('seeders.users'))->create()->each(function($user) {
            $user->stories()->saveMany(factory(App\Models\v1\Story::class, 2)->make(['author_id' => $user->id, 'author_type' => 'users']));
            $user->accolades()->save(factory(App\Models\v1\Accolade::class)->make());
            $user->accolades()->save(factory(App\Models\v1\Accolade::class, 'trip_history')->make());
            $user->essays()->save(factory(App\Models\v1\Essay::class)->make());
            $user->passports()->save(factory(App\Models\v1\Passport::class)->make());
            $user->visas()->save(factory(App\Models\v1\Visa::class)->make());
        });
    }
}
