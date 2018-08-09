<?php

namespace Tests\Feature\Api\Squads;

use Tests\TestCase;
use App\Models\v1\Squad;
use App\Jobs\NotifyOfPublishedSquads;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SquadPublicationTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function publish_a_squad()
    {
        $squad = factory(Squad::class)->create(['published' => false]);

        $response = $this->json('PUT', "api/squads/{$squad->uuid}", [
            'published' => true, 
            'callsign' => $squad->callsign, 
            'region_id' => $squad->region_id
        ]);

        $response->assertJson([
            'data' => [
                'status' => 'published'
            ]
        ]);
    }

    /** @test */
    public function unpublish_a_squad()
    {
        $squad = factory(Squad::class)->create(['published' => true]);

        $response = $this->json('PUT', "api/squads/{$squad->uuid}", [
            'published' => false, 
            'callsign' => $squad->callsign, 
            'region_id' => $squad->region_id
        ]);

        $response->assertJson([
            'data' => [
                'status' => 'draft'
            ]
        ]);
    }

    /** @test */
    public function bulk_publish_squads()
    {
        factory(Squad::class, 4)->create(['published' => false]);

        $response = $this->json('PUT', "api/squads/published", [
            'squads' => Squad::all()->pluck('uuid')->toArray(), 
            'published' => true
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('squads', ['published' => 1]);
        $this->assertDatabaseMissing('squads', ['published' => 0]);
    }

    /** @test */
    public function bulk_unpublish_squads()
    {
        factory(Squad::class, 4)->create(['published' => true]);

        $response = $this->json('PUT', "api/squads/published", [
            'squads' => Squad::all()->pluck('uuid')->toArray(), 
            'published' => false
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('squads', ['published' => 0]);
        $this->assertDatabaseMissing('squads', ['published' => 1]);
    }

    /** @test */
    public function sends_notifications_when_squads_are_published()
    {
        factory(Squad::class, 4)->create(['published' => false]);

        Queue::fake();

        $response = $this->json('PUT', "api/squads/published", [
            'squads' => Squad::all()->pluck('uuid')->toArray(), 
            'published' => true
        ]);

        $response->assertStatus(200);

        Queue::assertPushed(NotifyOfPublishedSquads::class);
    }

    /** @test */
    public function does_not_send_notifications_when_squads_are_unpublished()
    {
        factory(Squad::class, 4)->create(['published' => true]);

        Queue::fake();

        $response = $this->json('PUT', "api/squads/published", [
            'squads' => Squad::all()->pluck('uuid')->toArray(), 
            'published' => false
        ]);

        $response->assertStatus(200);

        Queue::assertNotPushed(NotifyOfPublishedSquads::class);
    }
}
