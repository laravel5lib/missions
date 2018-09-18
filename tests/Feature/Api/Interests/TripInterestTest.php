<?php

namespace Tests\Feature\Api\Interests;

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
                        'total_tasks_count',
                        'complete_tasks_count',
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

    /** @test */
    public function filter_interests_by_trip_tags()
    {
        $trip = factory(Trip::class)->create();
        $trip->syncTagsWithType(['Location: Lima', 'Flight Included'], 'trip');

        $this->assertContains('Location: Lima', $trip->tags->pluck('name')->toArray());
        $this->assertContains('Flight Included', $trip->tags->pluck('name')->toArray());

        $taggedInterest = factory(TripInterest::class)->create(['trip_id' => $trip->id]);
        $untaggedInterest = factory(TripInterest::class)->create();

        $response = $this->getJson("/api/interests?filter[trip_tags]=Location:+Lima,Flight+Included");

        $response->assertOk()
                 ->assertJson([
                    'data' => [
                        ['id' => $taggedInterest->id]
                    ]
                 ])
                 ->assertJsonMissing([
                    'data' => [
                        ['id' => $untaggedInterest->id]
                    ]
                 ]);
    }

    /** @test */
    public function delete_a_trip_interest_and_remove_from_storage()
    {
        $interest = factory(TripInterest::class)->create();

        $response = $this->deleteJson("/api/interests/{$interest->id}");

        $response->assertStatus(204);

        $this->assertNotNull($interest->fresh()->deleted_at);
    }
}
