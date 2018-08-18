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
    public function tags_must_be_in_array_format_to_update_trip()
    {
        $trip = factory(Trip::class)->create();

        $response = $this->json('PUT', "/api/trips/{$trip->id}", ['tags' => 'invalid']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['tags']);
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
    public function campaign_id_must_not_be_empty_to_update_trip()
    {
        $trip = factory(Trip::class)->create();

        $response = $this->putJson("/api/trips/{$trip->id}", ['campaign_id' => '']);

        $response->assertStatus(422)->assertJsonValidationErrors(['campaign_id']);
    }

     /** @test */
    public function campaign_id_must_be_valid_to_update_trip()
    {
        $trip = factory(Trip::class)->create();

        $response = $this->putJson("/api/trips/{$trip->id}", ['campaign_id' => 'invalid']);

        $response->assertStatus(422)->assertJsonValidationErrors(['campaign_id']);
    }

     /** @test */
    public function group_id_must_not_be_empty_to_update_trip()
    {
        $trip = factory(Trip::class)->create();

        $response = $this->putJson("/api/trips/{$trip->id}", ['group_id' => '']);

        $response->assertStatus(422)->assertJsonValidationErrors(['group_id']);
    }

     /** @test */
    public function group_id_must_be_valid_to_update_trip()
    {
        $trip = factory(Trip::class)->create();

        $response = $this->putJson("/api/trips/{$trip->id}", ['group_id' => 'invalid']);

        $response->assertStatus(422)->assertJsonValidationErrors(['group_id']);
    }

    /** @test */
    public function country_code_must_not_be_empty_to_update_trip()
    {
        $trip = factory(Trip::class)->create();

        $response = $this->putJson("/api/trips/{$trip->id}", ['country_code' => '']);

        $response->assertStatus(422)->assertJsonValidationErrors(['country_code']);
    }

    /** @test */
    public function country_code_must_be_valid_to_update_trip()
    {
        $trip = factory(Trip::class)->create();

        $response = $this->putJson("/api/trips/{$trip->id}", ['country_code' => 'invalid']);

        $response->assertStatus(422)->assertJsonValidationErrors(['country_code']);
    }

    /** @test */
    public function type_must_not_be_empty_to_update_trip()
    {
        $trip = factory(Trip::class)->create();

        $response = $this->putJson("/api/trips/{$trip->id}", ['type' => '']);

        $response->assertStatus(422)->assertJsonValidationErrors(['type']);
    }

    /** @test */
    public function difficulty_must_not_be_empty_to_update_trip()
    {
        $trip = factory(Trip::class)->create();

        $response = $this->putJson("/api/trips/{$trip->id}", ['difficulty' => '']);

        $response->assertStatus(422)->assertJsonValidationErrors(['difficulty']);
    }

    /** @test */
    public function difficulty_must_be_valid_to_update_trip()
    {
        $trip = factory(Trip::class)->create();

        $response = $this->putJson("/api/trips/{$trip->id}", ['difficulty' => 'invalid']);

        $response->assertStatus(422)->assertJsonValidationErrors(['difficulty']);
    }

    /** @test */
    public function started_at_must_not_be_empty_to_update_trip()
    {
        $trip = factory(Trip::class)->create();

        $response = $this->putJson("/api/trips/{$trip->id}", ['started_at' => '']);

        $response->assertStatus(422)->assertJsonValidationErrors(['started_at']);
    }

    /** @test */
    public function started_at_must_be_a_valid_date_to_update_trip()
    {
        $trip = factory(Trip::class)->create();

        $response = $this->putJson("/api/trips/{$trip->id}", ['started_at' => 'invalid']);

        $response->assertStatus(422)->assertJsonValidationErrors(['started_at']);
    }

    /** @test */
    public function started_at_must_be_a_date_before_the_ended_at_date_to_update_trip()
    {
        $trip = factory(Trip::class)->create();

        $response = $this->putJson("/api/trips/{$trip->id}", [
            'started_at' => now()->addDay()->toDateTimeString(),
            'ended_at' => now()->toDateTimeString()
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['started_at']);
    }

    /** @test */
    public function ended_at_must_not_be_empty_to_update_trip()
    {
        $trip = factory(Trip::class)->create();

        $response = $this->putJson("/api/trips/{$trip->id}", ['ended_at' => '']);

        $response->assertStatus(422)->assertJsonValidationErrors(['ended_at']);
    }

    /** @test */
    public function ended_at_must_be_a_valid_date_to_update_trip()
    {
        $trip = factory(Trip::class)->create();

        $response = $this->putJson("/api/trips/{$trip->id}", ['ended_at' => 'invalid']);

        $response->assertStatus(422)->assertJsonValidationErrors(['ended_at']);
    }

    /** @test */
    public function ended_at_must_be_a_date_after_the_started_at_date_to_update_trip()
    {
        $trip = factory(Trip::class)->create();

        $response = $this->putJson("/api/trips/{$trip->id}", [
            'ended_at' => now()->subDay()->toDateTimeString(), 
            'started_at' => now()->toDateTimeString()
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['ended_at']);
    }

    /** @test */
    public function team_roles_must_not_be_empty_to_update_trip()
    {
        $trip = factory(Trip::class)->create();

        $response = $this->putJson("/api/trips/{$trip->id}", ['team_roles' => []]);

        $response->assertStatus(422)->assertJsonValidationErrors(['team_roles']);
    }

    /** @test */
    public function team_roles_must_be_in_array_format_to_update_trip()
    {
        $trip = factory(Trip::class)->create();

        $response = $this->putJson("/api/trips/{$trip->id}", ['team_roles' => 'invalid']);

        $response->assertStatus(422)->assertJsonValidationErrors(['team_roles']);
    }
}
