<?php

namespace Tests\Feature\Api\Squads;

use Tests\TestCase;
use App\Models\v1\Squad;
use App\Models\v1\Region;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SquadTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function get_squads_by_region()
    {
        $region = factory(Region::class)->create();

        factory(Squad::class, 2)->create(['region_id' => $region->id]);
        factory(Squad::class)->create();

        $this->json('GET', "api/squads?filter[region_id]=$region->id")
             ->assertStatus(200)
             ->assertJsonStructure([
                 'data' => [
                     ['id', 'callsign', 'members_count', 'status', 'created_at', 'updated_at']
                 ],
                 'meta'
             ])
             ->assertJson([
                 'meta' => ['total' => 2]
             ]);
    }

    /** @test */
    public function get_squads_by_campaign()
    {
        $region = factory(Region::class)->create();

        factory(Squad::class, 2)->create(['region_id' => $region->id]);
        factory(Squad::class)->create();

        $this->json('GET', "api/squads?filter[campaign_id]=$region->campaign_id")
             ->assertStatus(200)
             ->assertJsonStructure([
                 'data' => [
                     ['id', 'callsign', 'members_count', 'status', 'created_at', 'updated_at']
                 ],
                 'meta'
             ])
             ->assertJson([
                 'meta' => ['total' => 2]
             ]);
    }

    /** @test */
    public function search_squads_by_callsign()
    {
        $aTeam = factory(Squad::class)->create(['callsign' => 'A Team']);
        $sealTeam = factory(Squad::class)->create(['callsign' => 'Seal Team Six']);

        $this->json('GET', "api/squads?filter[callsign]=seal")
             ->assertStatus(200)
             ->assertJson([
                 'data' => [
                     [
                        'id' => $sealTeam->uuid,
                        'callsign' => 'Seal Team Six',
                        'status' => 'draft'
                    ]
                 ],
                 'meta' => ['total' => 1]
             ]);
    }

    /** @test */
    public function get_squad_by_id()
    {
        $squad = factory(Squad::class)->create();

         $this->json('GET', "api/squads/$squad->uuid")
             ->assertStatus(200)
             ->assertJson([
                 'data' => ['id' => $squad->uuid ]
             ]);
    }

    /** @test */
    public function create_new_squad()
    {
        $region = factory(Region::class)->create();

        $this->json('POST', 'api/squads', [
            'region_id' => $region->id,
            'callsign' => 'Delta Force',
        ])
        ->assertStatus(201);

        $this->assertDatabaseHas('squads', ['region_id' => $region->id, 'callsign' => 'Delta Force']);
    }

    /** @test */
    public function require_region_id_and_callsign_to_create_squad()
    {
        $region = factory(Region::class)->create();

        $this->json('POST', 'api/squads', [
            'region_id' => null,
            'callsign' => '',
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['region_id', 'callsign']);
    }

    /** @test */
    public function require_valid_region_id_to_create_squad()
    {
        $region = factory(Region::class)->create();

        $this->json('POST', 'api/squads', [
            'region_id' => 'invalid',
            'callsign' => 'Team Xtreme',
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['region_id']);
    }

    /** @test */
    public function require_valid_callsign_to_create_squad()
    {
        $region = factory(Region::class)->create();

        $this->json('POST', 'api/squads', [
            'region_id' => $region->id,
            'callsign' => 'this is way way way too long for a callsign name which should only be about 60 chars.',
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['callsign']);
    }
    
    /** @test */
    public function require_a_unique_callsign_by_region_to_create_squad()
    {
        $region = factory(Region::class)->create();
        factory(Squad::class)->create(
            ['callsign' => 'Same callsign', 'region_id' => $region->id]
        );

        $this->json('POST', 'api/squads', [
            'region_id' => $region->id,
            'callsign' => 'Same callsign',
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['callsign']);
    }

    /** @test */
    public function update_a_squad()
    {
        $squad = factory(Squad::class)->create(['callsign' => 'Seal Team Six']);

        $this->json('PUT', "api/squads/{$squad->uuid}", ['callsign' => 'SAS'])
             ->assertStatus(200)
             ->assertJson([
                'data' => [
                    'callsign' => 'SAS'
                ]                 
             ]);
        
        $this->assertDatabaseHas('squads', [
            'id' => $squad->id, 'region_id' => $squad->region_id, 'callsign' => 'SAS'
        ]);
    }

    /** @test */
    public function cannot_update_a_squad_with_empty_callsign_or_region_id()
    {
        $squad = factory(Squad::class)->create(['callsign' => 'Seal Team Six']);

        $this->json('PUT', "api/squads/{$squad->uuid}", ['callsign' => '', 'region_id' => null])
             ->assertStatus(422)
             ->assertJsonValidationErrors(['callsign', 'region_id']);
    }

    /** @test */
    public function callsign_must_be_unqiue_when_updating_region_id()
    {
        $squad = factory(Squad::class)->create(['callsign' => 'Seal Team Six']);
        $squadTwo = factory(Squad::class)->create(['callsign' => 'Seal Team Six']);

        $this->json('PUT', "api/squads/{$squad->uuid}", [
            'callsign' => 'Seal Team Six', 'region_id' => $squadTwo->region_id
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['callsign']);
    }

    /** @test */
    public function delete_a_squad()
    {
        $squad = factory(Squad::class)->create();

        $this->json('DELETE', "api/squads/{$squad->uuid}")->assertStatus(204);

        $this->assertDatabaseMissing('squads', ['id' => $squad->id]);
    }
}
