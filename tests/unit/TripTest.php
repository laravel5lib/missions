<?php

use Carbon\Carbon;
use App\Models\v1\Group;
use App\Facades\Promocodes;
use App\Models\v1\Campaign;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TripTest extends TestCase
{
    /** 
     * @test
     */
    function trip_can_get_a_starting_cost()
    {
        $trip = factory(App\Models\v1\Trip::class)->create();

        $trip->each(function($t) {
            $t->costs()->saveMany([
                factory(App\Models\v1\Cost::class, 'deposit')->make([
                    'amount' => 100, 
                    'active_at' => Carbon::yesterday()
                ]),
                factory(App\Models\v1\Cost::class, 'general')->make([
                    'amount' => 2000, 
                    'active_at' => Carbon::yesterday()
                ])
            ]);
        });

        $this->assertSame(210000, $trip->starting_cost);
    }

    /** 
     * @test
     */
    function trip_can_get_starting_cost_in_dollars()
    {
        $trip = factory(App\Models\v1\Trip::class)->create();

        $trip->each(function($t) {
            $t->costs()->saveMany([
                factory(App\Models\v1\Cost::class, 'deposit')->make([
                    'amount' => 100, 
                    'active_at' => Carbon::yesterday()
                ]),
                factory(App\Models\v1\Cost::class, 'general')->make([
                    'amount' => 2000, 
                    'active_at' => Carbon::yesterday()
                ])
            ]);
        });

        $this->assertSame('2100.00', $trip->startingCostInDollars());
    }

    /** 
     * @test
     */
    function trip_with_no_spots_is_closed()
    {
        $trip = factory(App\Models\v1\Trip::class)->make([
            'spots' => 0,
            'closed_at' => Carbon::tomorrow()
        ]);

        $this->assertEquals('closed', $trip->status);
    }

    /** 
     * @test
     */
    function trip_past_closed_at_date_is_closed()
    {
        $trip = factory(App\Models\v1\Trip::class)->make([
            'spots' => 10,
            'closed_at' => Carbon::yesterday()
        ]);

        $this->assertEquals('closed', $trip->status);
    }

    /** 
     * @test
     */
    function trip_with_no_published_at_date_is_draft()
    {
        $trip = factory(App\Models\v1\Trip::class)->make([
            'published_at' => null,
            'spots' => 100,
            'closed_at' => Carbon::tomorrow()
        ]);

        $this->assertEquals('draft', $trip->status);
    }

    /** 
     * @test
     */
    function trip_with_future_published_at_date_is_scheduled()
    {
        $trip = factory(App\Models\v1\Trip::class)->make([
            'published_at' => Carbon::tomorrow(),
            'spots' => 50,
            'closed_at' => Carbon::tomorrow()
        ]);

        $this->assertEquals('scheduled', $trip->status);
    }

    /** 
     * @test
     */
    function trip_with_past_published_at_date_is_active()
    {
        $trip = factory(App\Models\v1\Trip::class)->make([
            'published_at' => Carbon::yesterday(),
            'spots' => 25,
            'closed_at' => Carbon::tomorrow()
        ]);

        $this->assertEquals('active', $trip->status);
    }

    /** @test */
    function apply_campaign_promocode()
    {
        $campaign = factory(Campaign::class, '1n1d2017')->create();

        $campaign->createCode('Campaign Promotional', 1, 10000);

        $trip = factory(App\Models\v1\Trip::class)->create([
            'campaign_id' => $campaign->id
        ]);

        $reward = $trip->applyCode($campaign->promocodes()->first()->code);

        $this->assertEquals(10000, $reward);
    }

    /** @test */
    function apply_group_promocode()
    {
        $group = factory(Group::class)->create();

        $group->createCode('Group Promotional', 1, 10000);

        $trip = factory(App\Models\v1\Trip::class)->create([
            'group_id' => $group->id
        ]);

        $reward = $trip->applyCode($group->promocodes()->first()->code);

        $this->assertEquals(10000, $reward);
    }

    /** @test */
    function apply_trip_promocode()
    {
        $trip = factory(App\Models\v1\Trip::class)->create();

        $trip->createCode('Trip Promotional', 1, 10000);

        $reward = $trip->applyCode($trip->promocodes()->first()->code);

        $this->assertEquals(10000, $reward);
    }

    /** @test */
    function apply_generic_promocode()
    {
        $promocode = Promocodes::create(
            $name = 'Generic', 
            $amount = 1, 
            $reward = 10000
        );

        $trip = factory(App\Models\v1\Trip::class)->create();

        $reward = $trip->applyCode($promocode->first()['code']);

        $this->assertEquals(10000, $reward);
    }

}
