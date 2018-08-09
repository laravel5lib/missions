<?php

namespace Tests\Feature\Api\Regions;

use Tests\TestCase;
use App\Models\v1\Squad;
use App\Models\v1\Region;
use App\Models\v1\Campaign;
use App\Models\v1\SquadMember;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegionTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function gets_all_regions_for_a_campaign()
    {
        $campaign = factory(Campaign::class)->create();

        factory(Region::class, 2)->create(['campaign_id' => $campaign->id]);
        factory(Region::class)->create();

        $this->assertEquals(Region::count(), 3);

        $this->json('GET', "/api/regions?filter[campaign_id]=$campaign->id")
             ->assertStatus(200)
             ->assertJsonStructure([
                 'data' => [
                     ['id', 'name', 'squads_count', 'created_at', 'updated_at', 'deleted_at']
                 ],
                 'meta'
             ])
             ->assertJson([
                 'meta' => ['total' => 2]
             ]);

    }

    /** @test */
    public function get_region_by_id()
    {
        $region = factory(Region::class)->create(['name' => 'Lima North']);

        $this->json('GET', "/api/regions/$region->id")
             ->assertStatus(200)
             ->assertJson([
                 'data' => [
                     'name' => 'Lima North'
                 ]
             ]);
    }

    /** @test */
    public function create_new_region()
    {
        $campaign = factory(Campaign::class)->create();

        $data = [
            'name' => 'Lima South',
            'campaign_id' => $campaign->id
        ];

        $this->json('POST', "/api/regions", $data)
             ->assertStatus(201);

        $this->assertDatabaseHas('regions', [
            'name' => 'Lima South', 
            'campaign_id' => $campaign->id, 
            'country_code' => $campaign->country_code
        ]);
    }

    /** @test */
    public function requires_name_and_campaign_id_to_create_a_new_region()
    {
        $this->json('POST', "/api/regions", [])
             ->assertStatus(422)
             ->assertJsonValidationErrors(['name', 'campaign_id']);
    }

    /** @test */
    public function updates_a_region()
    {
        $region = factory(Region::class)->create(['name' => 'Old Name']);

        $this->json('PUT', "/api/regions/$region->id", ['name' => 'New Name'])
             ->assertStatus(200)
             ->assertJson([
                 'data' => ['name' => 'New Name']
             ]);
        
        $this->assertDatabaseHas('regions', ['id' => $region->id, 'name' => 'New Name']);
    }

    /** @test */
    public function region_name_must_be_unique_to_the_campaign()
    {
        $region = factory(Region::class)->create(['name' => 'Same Name']);

        $this->json('POST', "/api/regions", [
            'name' => 'Same Name', 
            'campaign_id' => $region->campaign_id
        ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name']);

        $this->json('PUT', "/api/regions/$region->id", ['name' => 'Same Name'])->assertStatus(200);
    }

    /** @test */
    public function deletes_a_region_with_all_squads_and_members()
    {
        $region = factory(Region::class)->create();
        $squad = factory(Squad::class)->create(['region_id' => $region->id]);
        factory(SquadMember::class, 2)->create(['squad_id' => $squad->id]);

        $this->json('DELETE', "/api/regions/$region->id")
             ->assertStatus(204);

        $this->assertDatabaseMissing('regions', ['id' => $region->id]);
        $this->assertDatabaseMissing('squads', ['region_id' => $region->id]);
        $this->assertDatabaseMissing('squad_members', ['squad_id' => $squad->id]);
    }
}
