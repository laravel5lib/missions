<?php

namespace Tests\Feature\Api\Referrals;

use Tests\TestCase;
use App\Models\v1\Referral;
use App\Models\v1\Reservation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteReferralTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->withoutJobs();
    }

    /** @test */
    public function delete_referral_and_remove_from_storage()
    {
        $referral = factory(Referral::class)->create();

        $response = $this->deleteJson("/api/referrals/{$referral->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('referrals', ['id' => $referral->id]);
    }

    /** @test */
    public function detach_all_reservations_when_referral_is_deleted()
    {
        $referral = factory(Referral::class)->create();

        $reservation = factory(Reservation::class)->create();

        $referral->attachToReservation($reservation->id);

        $response = $this->deleteJson("/api/referrals/{$referral->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing(
            'reservation_documents', 
            [
                'reservation_id' => $reservation->id,
                'documentable_id' => $referral->id,
                'documentable_type' => 'referrals'
            ]
        );
    }
}
