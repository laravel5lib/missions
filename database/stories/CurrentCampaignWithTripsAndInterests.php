<?php

use App\Models\v1\Slug;
use App\Models\v1\User;
use App\Models\v1\Group;
use App\Models\v1\Story;
use App\Models\v1\Region;
use App\Models\v1\Campaign;
use App\Models\v1\TripInterest;
use FactoryStories\FactoryStory;
use App\Models\v1\Representative;

class CurrentCampaignWithTripsAndInterests extends FactoryStory
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
        $campaign = factory(Campaign::class)->create($params);

        $campaign->slug()->create(['url' => str_slug($campaign->name)]);

        factory(Region::class, 3)->create([
            'campaign_id'  => $campaign->id,
            'country_code' => $campaign->country_code
        ]);


        factory(Group::class, 10)->create()->each(function ($group) use ($campaign) {

            $group->slug()->create(['url' => generate_slug($group->name)]);

            $userId = array_rand(User::pluck('id', 'id')->toArray()) ?: (new UserWithEverything)->create()->id;

            $group->managers()->attach($userId);

            $group->stories()->saveMany(
                factory(Story::class, 2)->make(['author_id' => $group->id, 'author_type' => 'groups'])
            );

            $randomNumber = mt_rand(1, 3);

            $randomRep = Representative::all()->random() ?: factory(Representative::class)->create();

            foreach (range(1, $randomNumber) as $number) {
                (new RandomTrip)->create([
                    'group_id' => $group->id,
                    'campaign_id' => $campaign->id,
                    'rep_id' => $randomRep->id
                ]);
            }

            $group->trips->each(function ($trip) {

                factory(TripInterest::class, mt_rand(0, 5))
                    ->create(['trip_id' => $trip->id]);

                factory(TripInterest::class, 'converted', mt_rand(0, 5))
                    ->create(['trip_id' => $trip->id]);

                factory(TripInterest::class, 'declined', mt_rand(0, 5))
                    ->create(['trip_id' => $trip->id]);
            });
        });
    }
}
