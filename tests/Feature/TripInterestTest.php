<?php

namespace Tests\Feature;

use App\Models\v1\Campaign;
use App\Models\v1\Todo;
use App\Models\v1\Trip;
use App\Models\v1\TripInterest;
use App\Models\v1\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

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

    /** @test */
    public function filter_interests_by_incomplete_task()
    {
        factory(Todo::class, 2)->create([
            'todoable_type' => 'trip_interests', 
            'todoable_id' => function () {
                return factory(TripInterest::class)->create()->id;
            },
            'task' => '1st contact', 
            'completed_at' => null
        ]);
        factory(Todo::class, 3)->create([
            'todoable_type' => 'trip_interests', 
            'todoable_id' => function () {
                return factory(TripInterest::class)->create()->id;
            },
            'task' => '1st contact', 
            'completed_at' => now()->subDay()
        ]);
        factory(Todo::class, 4)->create([
            'todoable_type' => 'trip_interests', 
            'todoable_id' => function () {
                return factory(TripInterest::class)->create()->id;
            },
            'task' => '2nd contact', 
            'completed_at' => null
        ]);

        $this->json('GET', "/api/interests?filter[incomplete_task]=1st+contact")
             ->assertStatus(200)
             ->assertJson([
                    'meta' => [
                        'total' => 2
                    ]
                ]);
    }

    /** @test */
    public function filter_interests_by_complete_task()
    {
        factory(Todo::class, 2)->create([
            'todoable_type' => 'trip_interests', 
            'todoable_id' => function () {
                return factory(TripInterest::class)->create()->id;
            },
            'task' => '1st contact', 
            'completed_at' => null
        ]);
        factory(Todo::class, 3)->create([
            'todoable_type' => 'trip_interests', 
            'todoable_id' => function () {
                return factory(TripInterest::class)->create()->id;
            },
            'task' => '1st contact', 
            'completed_at' => now()->subDay()
        ]);
        factory(Todo::class, 4)->create([
            'todoable_type' => 'trip_interests', 
            'todoable_id' => function () {
                return factory(TripInterest::class)->create()->id;
            },
            'task' => '2nd contact', 
            'completed_at' => null
        ]);

        $this->json('GET', "/api/interests?filter[complete_task]=1st+contact")
             ->assertStatus(200)
             ->assertJson([
                    'meta' => [
                        'total' => 3
                    ]
                ]);
    }
}
