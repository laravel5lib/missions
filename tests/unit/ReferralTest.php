<?php

use Carbon\Carbon;
use App\Models\v1\Referral;

class ReferralTest extends BrowserKitTestCase
{

    /**
     * @test
     */
    function referral_with_null_sent_at_date_is_draft()
    {
        $referral = factory(Referral::class)->make(['sent_at' => null]);

        $this->assertEquals('draft', $referral->status);
    }

    /**
     * @test
     */
    function referral_with_a_sent_at_date_is_sent()
    {
        $referral = factory(Referral::class)->make(['sent_at' => Carbon::now()]);

        $this->assertEquals('sent', $referral->status);
    }

    /**
     * @test
     */
    function referral_with_a_responded_at_date_is_received()
    {
        $referral = factory(Referral::class)->make(['responded_at' => Carbon::now()]);

        $this->assertEquals('received', $referral->status);
    }
}
