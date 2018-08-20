<?php

namespace Tests\Feature\Api\Trips;

use Tests\TestCase;
use App\Models\v1\Trip;
use App\Models\v1\User;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateTripRequestTest extends TestCase
{
    use WithFaker;

    public function setUp()
    {
        parent::setUp();

        Passport::actingAs(new User);
    }

    /** @test */
    public function tags_must_be_in_array_format_to_update_trip()
    {
        $this->makeUpdateRequest(['tags' => 'invalid'])
             ->assertStatus(422)
             ->assertJsonValidationErrors(['tags']);
    }

    /** @test */
    public function campaign_id_must_not_be_empty_to_update_trip()
    {
        $this->makeUpdateRequest(['campaign_id' => ''])
             ->assertStatus(422)
             ->assertJsonValidationErrors(['campaign_id']);
    }

    /** @test */
    public function group_id_must_not_be_empty_to_update_trip()
    {
        $this->makeUpdateRequest(['group_id' => ''])
             ->assertStatus(422)
             ->assertJsonValidationErrors(['group_id']);
    }

    /** @test */
    public function country_code_must_not_be_empty_to_update_trip()
    {
        $this->makeUpdateRequest(['country_code' => ''])
             ->assertStatus(422)
             ->assertJsonValidationErrors(['country_code']);
    }

    /** @test */
    public function country_code_must_be_valid_to_update_trip()
    {
        $this->makeUpdateRequest(['country_code' => 'invalid'])
             ->assertStatus(422)
             ->assertJsonValidationErrors(['country_code']);
    }

    /** @test */
    public function type_must_not_be_empty_to_update_trip()
    {
        $this->makeUpdateRequest(['type' => ''])
             ->assertStatus(422)
             ->assertJsonValidationErrors(['type']);
    }

    /** @test */
    public function difficulty_must_not_be_empty_to_update_trip()
    {
        $this->makeUpdateRequest(['difficulty' => ''])
             ->assertStatus(422)
             ->assertJsonValidationErrors(['difficulty']);
    }

    /** @test */
    public function difficulty_must_be_valid_to_update_trip()
    {
        $this->makeUpdateRequest(['difficulty' => 'invalid'])
             ->assertStatus(422)
             ->assertJsonValidationErrors(['difficulty']);
    }

    /** @test */
    public function started_at_must_not_be_empty_to_update_trip()
    {
        $this->makeUpdateRequest(['started_at' => ''])
             ->assertStatus(422)
             ->assertJsonValidationErrors(['started_at']);
    }

    /** @test */
    public function started_at_must_be_a_valid_date_to_update_trip()
    {
        $this->makeUpdateRequest(['started_at' => 'invalid'])
             ->assertStatus(422)
             ->assertJsonValidationErrors(['started_at']);
    }

    /** @test */
    public function started_at_must_be_a_date_before_the_ended_at_date_to_update_trip()
    {
        $this->makeUpdateRequest([
            'started_at' => now()->addDay()->toDateTimeString(),
            'ended_at' => now()->toDateTimeString()
        ])
             ->assertStatus(422)
             ->assertJsonValidationErrors(['started_at']);
    }

    /** @test */
    public function ended_at_must_not_be_empty_to_update_trip()
    {
        $this->makeUpdateRequest(['ended_at' => ''])
             ->assertStatus(422)
             ->assertJsonValidationErrors(['ended_at']);
    }

    /** @test */
    public function ended_at_must_be_a_valid_date_to_update_trip()
    {
        $this->makeUpdateRequest(['ended_at' => 'invalid'])
             ->assertStatus(422)
             ->assertJsonValidationErrors(['ended_at']);
    }

    /** @test */
    public function ended_at_must_be_a_date_after_the_started_at_date_to_update_trip()
    {
        $this->makeUpdateRequest([
            'ended_at' => now()->subDay()->toDateTimeString(), 
            'started_at' => now()->toDateTimeString()
        ])
             ->assertStatus(422)
             ->assertJsonValidationErrors(['ended_at']);
    }

    /** @test */
    public function team_roles_must_not_be_empty_to_update_trip()
    {
        $this->makeUpdateRequest(['team_roles' => []])
             ->assertStatus(422)
             ->assertJsonValidationErrors(['team_roles']);
    }

    /** @test */
    public function team_roles_must_be_in_array_format_to_update_trip()
    {
        $this->makeUpdateRequest(['team_roles' => 'invalid'])
             ->assertStatus(422)
             ->assertJsonValidationErrors(['team_roles']);
    }

    private function makeUpdateRequest($data = [])
    {
        return $this->json('PUT', "/api/trips/{$this->faker->uuid}", $data);
    }
}
