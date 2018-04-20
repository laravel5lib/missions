<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\v1\Cost;
use App\Models\v1\User;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CostTest extends TestCase
{
    /** @test */
    public function creates_a_new_cost()
    {
        Passport::actingAs(factory(User::class)->create());

        $response = $this->json('post', '/api/costs', [
            'name' => 'General Registration',
            'description' => 'The standard cost for general trip registration.',
            'type' => 'incremental'
        ]);

        $response->assertStatus(201);
    }

    /** @test */
    public function gets_all_costs()
    {
        Passport::actingAs(factory(User::class)->create());
        
        factory(Cost::class, 2)->create();

        $response = $this->json('get', '/api/costs');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                ['name', 'description', 'type']
            ]
        ]);
    }

    /** @test */
    public function gets_a_cost_by_id()
    {
        Passport::actingAs(factory(User::class)->create());
        
        $cost = factory(Cost::class)->create([
            'name' => 'Test cost',
            'description' => 'Test description',
            'type' => 'incremental'
        ]);

        $response = $this->json('get', "/api/costs/{$cost->id}");

        $response->assertStatus(200);
        $response->assertExactJson([
            'data' => [
                'id' => $cost->id,
                'name' => 'Test cost',
                'description' => 'Test description',
                'type' => 'incremental'
            ]
        ]);
    }

    /** @test */
    public function updates_a_cost_by_id()
    {
        Passport::actingAs(factory(User::class)->create());
        
        $cost = factory(Cost::class)->create([
            'name' => 'Test cost',
            'description' => 'Test description',
            'type' => 'incremental'
        ]);

        $response = $this->json('put', "/api/costs/{$cost->id}", [
            'name' => 'Updated cost'
        ]);

        $response->assertStatus(200);
        $response->assertExactJson([
            'data' => [
                'id' => $cost->id,
                'name' => 'Updated cost',
                'description' => 'Test description',
                'type' => 'incremental'
            ]
        ]);
    }
}
