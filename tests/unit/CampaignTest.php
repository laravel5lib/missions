<?php

use Carbon\Carbon;
use App\Models\v1\Trip;
use App\Models\v1\Campaign;
use App\Models\v1\Reservation;

class CampaignTest extends BrowserKitTestCase
{

    /**
     * @test
     */
    function campaign_with_no_published_at_date_is_draft()
    {
        $campaign = factory(Campaign::class, '1n1d2017')->make([
            'published_at' => null
        ]);

        $this->assertEquals('Draft', $campaign->status);
    }

    /**
     * @test
     */
    function campaign_with_future_published_at_date_is_scheduled()
    {
        $campaign = factory(Campaign::class, '1n1d2017')->make([
            'published_at' => Carbon::tomorrow(),
        ]);

        $this->assertEquals('Scheduled', $campaign->status);
    }

    /**
     * @test
     */
    function campaign_with_past_published_at_date_is_published()
    {
        $campaign = factory(Campaign::class, '1n1d2017')->make([
            'published_at' => Carbon::yesterday()
        ]);

        $this->assertEquals('Published', $campaign->status);
    }

    /** @test */
    function creates_promocodes()
    {
        $campaign = factory(Campaign::class, '1n1d2017')->create();

        $campaign->promote('Recruit1', 1, 10000);

        $promotional = $campaign->promotionals()->first();

        $this->assertEquals(
            $campaign->id,
            $campaign->promotionals()->first()->promoteable_id
        );
        $this->assertEquals(
            $promotional->id,
            $promotional->promocodes()->first()->promotional_id
        );
    }

    /** @test */
    function create_promocodes_with_affiliates()
    {
        $campaign = factory(Campaign::class, '1n1d2017')->create();
        $trip = factory(Trip::class)->create(['campaign_id' => $campaign->id]);
        $reservations = factory(Reservation::class, 2)->create(['trip_id' => $trip->id]);

        $campaign->promote(
            $name = 'Recruit1',
            $qty = 1,
            $reward = 10000,
            $expires = null,
            $affilates = 'reservations'
        );

        $this->assertEquals(
            'reservations',
            $campaign->promotionals()->first()->promocodes()->first()->rewardable_type
        );
    }
}
