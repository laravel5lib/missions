<?php

class TransportsEndpointTest extends TestCase
{
    /** @test */
    public function gets_all_transports()
    {
        $campaign = factory(\App\Models\v1\Campaign::class)->create();
        factory(\App\CampaignTransport::class, 2)->create(['campaign_id' => $campaign->id]);

        $this->get('/api/campaigns/'.$campaign->id.'/transports');

        $this->assertResponseOk()
            ->seeJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'type',
                    'vessel_no',
                    'name',
                    'domestic',
                    'capacity',
                    'passengers',
                    'call_sign',
                    'depart_at',
                    'arrive_at',
                    'created_at',
                    'updated_at',
                    'deleted_at'
                ]
            ]
        ]);
    }

    /** @test */
    public function gets_transport_by_id()
    {
        $campaign = factory(\App\Models\v1\Campaign::class)->create();
        $transport = factory(\App\CampaignTransport::class)->create(['campaign_id' => $campaign->id]);

        $this->get('/api/campaigns/'.$campaign->id.'/transports/'.$transport->id);

        $this->assertResponseOk()->seeJson([
            'id' => $transport->id
        ]);
    }

    /** @test */
    public function creates_transport()
    {
        $campaign = factory(\App\Models\v1\Campaign::class)->create();
        $transport = [
            'name' => 'AMERICAN airlines',
            'type' => 'flight',
            'call_sign' => 'aa',
            'vessel_no' => '300',
            'domestic' => false,
            'depart_at' => '2017-07-22 10:30:01',
            'arrive_at' => '2017-07-22 22:45:05'
        ];

        $this->post('/api/campaigns/'.$campaign->id.'/transports/', $transport);

        $this->assertResponseOk()->seeJson([
            'name' => 'American Airlines',
            'type' => 'flight',
            'call_sign' => 'AA',
            'vessel_no' => 'AA0300',
            'domestic' => false,
            'depart_at' => '2017-07-22 10:30:00',
            'arrive_at' => '2017-07-22 22:45:00'
        ]);
    }

    /** @test */
    public function updates_transport()
    {
        $campaign = factory(\App\Models\v1\Campaign::class)->create();
        $transport = factory(\App\CampaignTransport::class)->create([
            'vessel_no' => 'AA0300',
            'campaign_id' => $campaign->id
        ]);

        $this->put('/api/campaigns/'.$campaign->id.'/transports/'. $transport->id, [
            'name' => 'Delta Airlines',
            'call_sign' => 'DL'
        ]);

        $this->assertResponseOk()->seeJson([
            'name' => 'Delta Airlines',
            'call_sign' => 'DL',
            'vessel_no' => 'DL0300'
        ]);
    }

    /** @test */
    public function deletes_transport()
    {
        $campaign = factory(\App\Models\v1\Campaign::class)->create();
        $transport = factory(\App\CampaignTransport::class)->create([
            'campaign_id' => $campaign->id
        ]);

        $this->delete('/api/campaigns/'.$campaign->id.'/transports/'. $transport->id);

        $this->assertResponseStatus(204)
            ->seeInDatabase('campaign_transports', ['id' => $transport->id])
            ->dontSeeInDatabase('campaign_transports', [
                'id' => $transport->id,
                'deleted_at' => null
            ]);
    }
}
