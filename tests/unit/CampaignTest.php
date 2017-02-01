<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CampaignTest extends TestCase
{
    use DatabaseTransactions;

    /* @test */
    function campaign_can_set_a_started_at_date()
    {
        //
    }

    /* @test */
    function campaign_can_set_an_ended_at_date()
    {
        //
    }

    /* @test */
    function campaign_with_a_future_ended_at_date_is_active()
    {
        //
    }

    /* @test */
    function campaign_with_a_past_ended_at_date_is_inactive()
    {
        //
    }

    /* @test */
    function campaign_with_a_past_or_present_published_at_date_is_public()
    {
        //
    }

}
