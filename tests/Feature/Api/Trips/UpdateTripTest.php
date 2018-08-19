<?php

namespace Tests\Feature\Api\Trips;

use Tests\TestCase;
use App\Models\v1\Trip;
use App\Models\v1\User;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateTripTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        Passport::actingAs(factory(User::class, 'admin')->create());
    }
    
    /** @test */
    public function updates_a_trip()
    {
        $trip = factory(Trip::class)->create([
            'type' => 'ministry', 
            'difficulty' => 'level_2'
        ]);

        $this->json('PUT', "/api/trips/{$trip->id}", [
            'type' => 'family',
            'difficulty' => 'level_1'
        ])
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                'type' => 'family',
                'difficulty' => 'level_1'
            ]
        ]);
    }

    /** @test */
    public function update_trip_tags()
    {
        $trip = factory(Trip::class)->create();
        $trip->syncTagsWithType(['amazon region', 'flight included'], 'trip');

        $response = $this->json('PUT', "/api/trips/{$trip->id}", ['tags' => ['amazon region', 'with Maccu Picchu']]);

        $response->assertStatus(200)
                ->assertJson([
                    'data' => [
                        'tags' => [
                            ['name' => 'amazon region'],
                            ['name' => 'with Maccu Picchu']
                        ]
                    ]
                ])
                ->assertJsonMissing([
                    'data' => [
                        'tags' => [
                            ['name' => 'flight included']
                        ]
                    ]
                ]);
    }

    /** @test */
    public function remove_all_tags_from_trip()
    {
        $trip = factory(Trip::class)->create();
        $trip->syncTagsWithType(['amazon region', 'flight included'], 'trip');

        $response = $this->json('PUT', "/api/trips/{$trip->id}", ['tags' => []]);

        $response->assertStatus(200)
                ->assertJson([
                    'data' => [
                        'tags' => []
                    ]
                ]);
    }

     /** @test */
    public function campaign_id_must_be_valid_to_update_trip()
    {
        $trip = factory(Trip::class)->create();

        $response = $this->putJson("/api/trips/{$trip->id}", ['campaign_id' => 'invalid']);

        $response->assertStatus(422)->assertJsonValidationErrors(['campaign_id']);
    }

     /** @test */
    public function group_id_must_be_valid_to_update_trip()
    {
        $trip = factory(Trip::class)->create();

        $response = $this->putJson("/api/trips/{$trip->id}", ['group_id' => 'invalid']);

        $response->assertStatus(422)->assertJsonValidationErrors(['group_id']);
    }
}
