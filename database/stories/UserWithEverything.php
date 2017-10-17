<?php

use App\Models\v1\Link;
use App\Models\v1\Slug;
use App\Models\v1\User;
use App\Models\v1\Visa;
use App\Models\v1\Story;
use App\Models\v1\Essay;
use App\Models\v1\Accolade;
use App\Models\v1\Passport;
use App\Models\v1\Referral;
use FactoryStories\FactoryStory;
use App\Models\v1\MedicalRelease;

class UserWithEverything extends FactoryStory
{
    /**
     * Here you can create your complex model factory
     * logic
     *
     * @param array $params Array of params
     *
     * @return Mixed
     */
    public function build($params = [])
    {
        $user = factory(User::class)->create();

        $user->slug()
            ->save(factory(Slug::class)->make([
                'url' => str_slug($user->name)
            ]));

        $user->links()->saveMany([
            factory(Link::class)->make(['name' => 'facebook', 'url' => 'https://facebook.com']),
            factory(Link::class)->make(['name' => 'twitter', 'url' => 'https://twitter.com']),
            factory(Link::class)->make()
        ]);

        $user->stories()->saveMany(
            factory(Story::class, 3)->make(['author_id' => $user->id, 'author_type' => 'users'])
        );

        $user->accolades()->save(factory(Accolade::class)->make());
        $user->accolades()->save(factory(Accolade::class, 'trip_history')->make());

        $user->essays()->save(factory(Essay::class)->make());
        $user->passports()->save(factory(Passport::class)->make());
        $user->visas()->save(factory(Visa::class)->make());
        $user->referrals()->save(factory(Referral::class)->make());
        $user->medicalReleases()->save(factory(MedicalRelease::class)->make());

        return $user;
    }
}
