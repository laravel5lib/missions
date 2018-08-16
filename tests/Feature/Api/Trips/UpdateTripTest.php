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
    
    /** @test */
    public function updates_a_trip()
    {
        Passport::actingAs(factory(User::class, 'admin')->create());

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
}
