<?php

use App\Models\v1\Campaign;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CampaignsControllerTest extends TestCase
{
    /** @test */
    public function creates_a_campaign()
    {
        $campaign = [
            'name' => 'Foo Bar',
            'country_code' => 'us',
            'short_desc' => 'Baz foo bar lorem ipsum',
            'page_url' => 'foo-bar',
            'page_src' => '_baz',
            'started_at' => '2017-01-01',
            'ended_at' => '2017-01-07',
            'published_at' => '2017-01-01'
        ];

        $this->post('/api/campaigns', $campaign);

        $this->seeInDatabase('campaigns', array_except($campaign, ['page_url']))
            ->seeInDatabase('slugs', ['url' => 'foo-bar']);
    }

    /** @test */
    public function updates_the_campaign()
    {
        $campaign = factory(Campaign::class)->create();
        $campaign->slug()->create(['url' => 'bar-foo']);

        $this->put('/api/campaigns/'.$campaign->id, [
            'name' => 'Baz', 'page_url' => 'baz-foo-bar'
        ]);

        $this->seeInDatabase('campaigns', ['id' => $campaign->id, 'name' => 'Baz'])
            ->seeInDatabase('slugs', ['slugable_id' => $campaign->id, 'url' => 'baz-foo-bar']);
    }
}
