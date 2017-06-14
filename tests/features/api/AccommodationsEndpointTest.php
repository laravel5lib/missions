<?php

use App\Models\v1\Region;
use App\Models\v1\Campaign;
use App\Models\v1\Accommodation;

class AccommodationsEndpointTest extends TestCase
{
    /** @test */
    public function gets_accommodations()
    {
        $campaign = factory(Campaign::class)->create();
        $region = factory(Region::class)->create(['campaign_id' => $campaign->id]);
        $accommodation = factory(Accommodation::class)->create(['region_id' => $region->id]);
        
        $this->get('/api/regions/'. $region->id .'/accommodations')
             ->assertResponseOk()
             ->seeJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'region_id',
                        'name',
                        'rooms_count',
                        'occupants_count',
                        'address_one',
                        'address_two',
                        'city',
                        'state',
                        'zip',
                        'phone',
                        'fax',
                        'country' => ['code', 'name'],
                        'email',
                        'url',
                        'short_desc',
                        'created_at',
                        'updated_at',
                        'deleted_at'
                    ]
                ]
            ]);
    }

    /** @test */
    public function gets_accommodation_by_id()
    {
        $campaign = factory(Campaign::class)->create();
        $region = factory(Region::class)->create(['campaign_id' => $campaign->id]);
        $accommodation = factory(Accommodation::class)->create(['region_id' => $region->id]);

        $this->get('/api/regions/'. $region->id .'/accommodations')
             ->assertResponseOk()
             ->seeJson(['id' => $accommodation->id]);
    }

    /** @test */
    public function creates_accommodation()
    {
        $campaign = factory(Campaign::class)->create();
        $region = factory(Region::class)->create(['campaign_id' => $campaign->id]);

        $data = [
            'name' => 'MARRIOTT hotel',
            'country_code' => 'us'
        ];

        $this->post('/api/regions/'. $region->id .'/accommodations', $data)
             ->assertResponseOk()
             ->seeJson(['name' => 'Marriott Hotel']);
    }

    /** @test */
    public function update_accommodation()
    {
        $campaign = factory(Campaign::class)->create();
        $region = factory(Region::class)->create(['campaign_id' => $campaign->id]);
        $accommodation = factory(Accommodation::class)->create([
            'region_id' => $region->id,
            'short_desc' => 'Orginal description'
        ]);

        $changes = ['short_desc' => 'Updated description. '];

        $this->put('/api/regions/'. $region->id .'/accommodations/'.  $accommodation->id, $changes)
             ->assertResponseOk()
             ->seeJson(['short_desc' => 'Updated description.']);
    }

    /** @test */
    public function deletes_accommodation()
    {
        $accommodation = factory(Accommodation::class)->create();

        $this->delete('/api/regions/'. $accommodation->region_id .'/accommodations/'.  $accommodation->id)
             ->assertResponseStatus(204)
             ->seeInDatabase('accommodations', ['id' => $accommodation->id])
             ->dontSeeInDatabase('accommodations', ['id' => $accommodation->id, 'deleted_at' => null]);
    }
}
