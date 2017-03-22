<?php

use Carbon\Carbon;
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

}
