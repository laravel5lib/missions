<?php

use Carbon\Carbon;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CampaignTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;
    
    /**
     * @test
     */
    function campaign_with_no_published_at_date_is_draft()
    {
        $campaign = factory(App\Models\v1\Campaign::class, '1n1d2017')->make([
            'published_at' => null
        ]);

        $this->assertEquals('Draft', $campaign->status);
    }

    /**
     * @test
     */
    function campaign_with_future_published_at_date_is_scheduled()
    {
        $campaign = factory(App\Models\v1\Campaign::class, '1n1d2017')->make([
            'published_at' => Carbon::tomorrow()
        ]);

        $this->assertEquals('Scheduled', $campaign->status);
    }

    /**
     * @test
     */
    function campaign_with_past_published_at_date_is_published()
    {
        $campaign = factory(App\Models\v1\Campaign::class, '1n1d2017')->make([
            'published_at' => Carbon::yesterday()
        ]);

        $this->assertEquals('Published', $campaign->status);
    }

}
