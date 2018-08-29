<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\v1\Referral;
use Illuminate\Support\Facades\Queue;
use App\Jobs\SendReferralRequestEmail;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReferralTest extends TestCase
{
    /** @test */
    public function dispatches_job_to_send_an_email_when_a_referral_is_created()
    {
        Queue::fake();

        $referral = factory(Referral::class)->create(['recipient_email' => 'john@gmail.com']);

        Queue::assertPushed(SendReferralRequestEmail::class, function ($job) use ($referral) {
            return $job->referral->email === $referral->email;
        });

        $this->assertNotNull($referral->sent_at);
    }

    /** @test */
    public function dispatches_job_to_send_an_email_when_a_updating_a_referral_with_no_response()
    {
        $referral = factory(Referral::class)->create(['responded_at' => null]);

        Queue::fake();

        $referral->update(['recipient_email' => 'john@gmail.com']);

        Queue::assertPushed(SendReferralRequestEmail::class);
    }

    /** @test */
    public function does_not_dispatch_job_to_send_an_email_when_a_updating_a_referral_already_responsed_to()
    {
        $referral = factory(Referral::class)->create(['responded_at' => now()->toDateTimeString()]);

        Queue::fake();

        $referral->update(['recipient_email' => 'john@gmail.com']);

        Queue::assertNotPushed(SendReferralRequestEmail::class);
    }
}
