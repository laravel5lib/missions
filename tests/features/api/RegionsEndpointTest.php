<?php

use App\Models\v1\Region;
use App\Models\v1\Campaign;

class RegionsEndpointTest extends TestCase
{
    /** @test */
    public function gets_all_regions()
    {
        $campaign = factory(Campaign::class)->create();
        $region = factory(Region::class)->create(['campaign_id' => $campaign->id]);

        $this->get('/api/campaigns/' . $campaign->id . '/regions')
             ->assertResponseOk()
             ->seeJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'callsign',
                        'country' => [
                            'code',
                            'name'
                        ],
                        'campaign_id',
                        // 'teams',
                        // 'accommodations',
                        'created_at',
                        'updated_at',
                        'deleted_at'
                    ]
                ]
            ]);
    }

    /** @test */
    public function get_region_by_id()
    {
        $campaign = factory(Campaign::class)->create();
        $region = factory(Region::class)->create(['campaign_id' => $campaign->id]);

        $this->get('/api/campaigns/' . $campaign->id . '/regions/' . $region->id)
             ->assertResponseOk()
             ->seeJson(['id' => $region->id]);
    }

    /** @test */
    public function create_new_region()
    {
        $campaign = factory(Campaign::class)->create();

        $data = [
            'name' => 'Test REGION',
            'callsign' => 'blue'
        ];

        $this->post('/api/campaigns/' . $campaign->id . '/regions', $data)
             ->assertResponseOk()
             ->seeJson(['name' => 'Test Region', 'callsign' => 'Blue']);
    }

    /** @test */
    public function update_region()
    {
        $campaign = factory(Campaign::class)->create();
        $region = factory(Region::class)->create([
            'campaign_id' => $campaign->id, 
            'name' => 'New Region',
            'callsign' => 'Red'
        ]);

        $changes = [
            'name' => 'Updated REGION'
        ];

        $this->put('/api/campaigns/' . $campaign->id . '/regions/' . $region->id, $changes)
             ->assertResponseOk()
             ->seeJson(['name' => 'Updated Region', 'callsign' => 'Red']);
    }

    /** @test */
    public function delete_region()
    {
        $campaign = factory(Campaign::class)->create();
        $region = factory(Region::class)->create(['campaign_id' => $campaign->id]);

        $this->delete('/api/campaigns/' . $campaign->id . '/regions/' . $region->id)
             ->assertResponseStatus(204)
             ->seeInDatabase('regions', ['id' => $region->id])
             ->dontSeeInDatabase('regions', ['id' => $region->id, 'deleted_at' => null]);
    }
}
