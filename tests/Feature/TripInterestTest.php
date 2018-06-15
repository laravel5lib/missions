<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\v1\Trip;
use App\Models\v1\User;
use App\Models\v1\Campaign;
use Laravel\Passport\Passport;
use App\Models\v1\TripInterest;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TripInterestTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function gets_all_interest_for_a_campaign()
    {
        Passport::actingAs(factory(User::class)->create());
        
        $campaign = factory(Campaign::class)->create();

        factory(Trip::class, 2)->create(['campaign_id' => $campaign->id]);

        Trip::all()->each(function ($trip) {
            factory(TripInterest::class, 2)->create(['trip_id' => $trip->id]);
        });

        factory(TripInterest::class)->create();

        $this->assertEquals(TripInterest::count(), 5);

        $this->json('GET', "/api/interests?filter[campaign]=$campaign->id")
             ->assertStatus(200)
             ->assertJsonStructure([
                 'data' => [
                    [
                        'id', 
                        'trip_id', 
                        'status', 
                        'name', 
                        'email', 
                        'phone', 
                        'communication_preferences', 
                        'created_at', 
                        'updated_at'
                    ]
                ]
             ])
            ->assertJson([
                'meta' => ['total' => 4]
            ]);
    }

    public function creates_new_trip_interest()
    {
        //
    }
}
