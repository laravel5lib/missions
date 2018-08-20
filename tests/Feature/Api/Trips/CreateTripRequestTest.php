<?php

namespace Tests\Feature\Api\Trips;

use Tests\TestCase;
use App\Models\v1\Trip;
use App\Models\v1\User;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateTripRequestTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        Passport::actingAs(new User);
    }

    /** @test */
    public function tags_must_be_in_array_format_to_create_trip()
    {
        $response = $this->json('POST', '/api/trips', ['tags' => 'invalid']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['tags']);
    }

    /** @test */
    public function requires_specific_fields_to_create_trip()
    {
        $this->json('POST', '/api/trips', [
            'companion_limit' => 0,
            'spots' => 25,
            'closed_at' => null,
            'default_prices' => false,
            'prospects' => ['adults', 'young adults (18-29)', 'men', 'women']
        ])
        ->assertJsonValidationErrors([
            'campaign_id', 'group_id', 'country_code', 'type', 'difficulty', 'started_at', 'ended_at', 'team_roles'
        ]);
    }

    /** @test */
    public function dates_must_be_in_logical_order_to_create_trip()
    {        
        $this->json('POST', '/api/trips', [
            'started_at' => today()->addDays(5)->toDateString(),
            'ended_at' => today()->toDateString(),
            'closed_at' => today()->addDays(10)->toDateString(),
        ])
        ->assertJsonValidationErrors([
            'started_at', 'ended_at', 'closed_at'
        ]);
    }

    /** @test */
    public function team_roles_and_todos_and_prospects_must_be_in_array_format_to_create_trip()
    {     
        $this->json('POST', '/api/trips', [
            'team_roles' => 'invalid',
            'prospects' => 'invalid',
            'todos' => 'invalid'
        ])
        ->assertJsonValidationErrors([
            'team_roles', 'prospects', 'todos'
        ]);
    }
    
    /** @test */
    public function published_at_must_be_a_valid_date_to_create_trip()
    {    
        $this->json('POST', '/api/trips', [
            'published_at' => 'invalid',
        ])
        ->assertJsonValidationErrors([
            'published_at'
        ]);
    }
}
