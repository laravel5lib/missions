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

    /** @test */
    public function deletes_cost_by_id()
    {
        Passport::actingAs(factory(User::class)->create());
        
        $cost = factory(Cost::class)->create();

        $response = $this->json('delete', "/api/costs/{$cost->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('costs', ['id' => $cost->id]);
    }

    /** @test */
    public function only_authenticated_user_can_get_costs()
    {
        factory(Cost::class, 2)->create();

        $response = $this->json('get', '/api/costs');
        $response->assertStatus(401);

        $cost = factory(Cost::class)->create();

        $response = $this->json('get', "/api/costs/{$cost->id}");
        $response->assertStatus(401);
    }

    /** @test */
    public function only_authenticated_user_can_create_costs()
    {
        $response = $this->json('post', "/api/costs/", [
            'name' => 'Test cost',
            'description' => 'Test description',
            'type' => 'incremental'
        ]);

        $response->assertStatus(401);
    }

    /** @test */
    public function only_authenticated_user_can_update_costs()
    {
        $cost = factory(Cost::class)->create();

        $response = $this->json('put', "/api/costs/{$cost->id}", [
            'name' => 'Update cost'
        ]);

        $response->assertStatus(401);
    }

    /** @test */
    public function only_authenticated_user_can_delete_costs()
    {
        $cost = factory(Cost::class)->create();

        $response = $this->json('delete', "/api/costs/{$cost->id}");

        $response->assertStatus(401);
    }

    /** @test */
    public function validates_request_to_create_cost()
    {
        Passport::actingAs(factory(User::class)->create());

        $response = $this->json('post', '/api/costs', [
            'type' => 'invalid'
        ]);

        $response->assertJsonValidationErrors(['name', 'type', 'description']);
    }

    /** @test */
    public function validates_request_to_update_cost()
    {
        Passport::actingAs(factory(User::class)->create());

        $cost = factory(Cost::class)->create();

        $response = $this->json('put', "/api/costs/{$cost->id}", [
            'name' => 123,
            'type' => 'invalid',
            'description' => 'This is description is way way way too long for a cost description. This should be 120 characters or less but it is a whole lot more than that!!!!'
        ]);

        $response->assertJsonValidationErrors(['name', 'type', 'description']);
    }
}
