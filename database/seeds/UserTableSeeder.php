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
        factory(App\Models\v1\User::class, 'admin')->create()->each(function ($user) {
            $user->slug()
                 ->save(factory(App\Models\v1\Slug::class)
                 ->make(['url' => str_slug($user->name)]));
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
            $user->referrals()->save(factory(App\Models\v1\Referral::class)->make());
            $user->medicalReleases()->save(factory(App\Models\v1\MedicalRelease::class)->make());
        });
        // factory(App\Models\v1\User::class, config('seeders.users'))->create()->each(function($user) {
        //     $user->slug()
        //          ->save(factory(App\Models\v1\Slug::class)
        //          ->make(['url' => str_slug($user->name)]));
        //     $user->stories()->saveMany(factory(App\Models\v1\Story::class, 2)->make(['author_id' => $user->id, 'author_type' => 'users']));
        //     $user->accolades()->save(factory(App\Models\v1\Accolade::class)->make());
        //     $user->accolades()->save(factory(App\Models\v1\Accolade::class, 'trip_history')->make());
        //     $user->essays()->save(factory(App\Models\v1\Essay::class)->make());
        //     $user->passports()->save(factory(App\Models\v1\Passport::class)->make());
        //     $user->visas()->save(factory(App\Models\v1\Visa::class)->make());
        //     $user->referrals()->save(factory(App\Models\v1\Referral::class)->make());
        //     $user->medicalReleases()->save(factory(App\Models\v1\MedicalRelease::class)->make());
        // });
    }
}
