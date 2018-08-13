<?php

namespace Tests\Feature\Api\Referrals;

use Tests\TestCase;
use App\Models\v1\Referral;
use App\Models\v1\Reservation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateReferralTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->withoutJobs();
    }

    /** @test */
    public function update_referral_and_save_changes_in_storage()
    {
        $referral = factory(Referral::class)->create(['recipient_email' => 'jdoe@gmail.com']);

        $response = $this->putJson("/api/referrals/{$referral->id}", ['recipient_email' => 'john@gmail.com']);

        $response->assertOk()
                 ->assertJson([
                    'data' => [
                        'id' => $referral->id,
                        'recipient_email' => 'john@gmail.com'
                    ]
                 ]);

        $this->assertDatabaseHas(
            'referrals', 
            ['id' => $referral->id, 'recipient_email' => 'john@gmail.com']
        );
    }

    /** @test */
    public function attach_referral_to_reservation_when_updated()
    {
        $reservation = factory(Reservation::class)->create();

        $release = factory(Referral::class)->create([
            'applicant_name' => 'John Doe', 
        ]);

        $response = $this->putJson("/api/referrals/{$release->id}", [
            'applicant_name' => 'James Smith',
            'reservation_id' => $reservation->id
        ]);

        $response->assertOk();

        $this->assertDatabaseHas(
            'reservation_documents', 
            ['reservation_id' => $reservation->id, 'documentable_type' => 'referrals']
        );
    }    

    /** @test */
    public function user_id_must_not_be_empty_to_update_referral()
    {
        $referral = factory(Referral::class)->create();

        $response = $this->putJson("/api/referrals/{$referral->id}", ['user_id' => '']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['user_id']);
    }

    /** @test */
    public function user_id_must_be_valid_to_update_referral()
    {
        $referral = factory(Referral::class)->create();

        $response = $this->putJson("/api/referrals/{$referral->id}", ['user_id' => 'invalid']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['user_id']);
    }

    /** @test */
    public function applicant_name_must_not_be_empty_to_update_referral()
    {
        $referral = factory(Referral::class)->create();

        $response = $this->putJson("/api/referrals/{$referral->id}", ['applicant_name' => '']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['applicant_name']);
    }

    /** @test */
    public function type_must_not_be_empty_to_update_referral()
    {
        $referral = factory(Referral::class)->create();

        $response = $this->putJson("/api/referrals/{$referral->id}", ['type' => '']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['type']);
    }

    /** @test */
    public function attention_to_must_not_be_empty_to_update_referral()
    {
        $referral = factory(Referral::class)->create();

        $response = $this->putJson("/api/referrals/{$referral->id}", ['attention_to' => '']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['attention_to']);
    }

    /** @test */
    public function recipient_email_must_not_be_empty_to_update_referral()
    {
        $referral = factory(Referral::class)->create();

        $response = $this->putJson("/api/referrals/{$referral->id}", ['recipient_email' => '']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['recipient_email']);
    }

    /** @test */
    public function referrer_must_not_be_empty_to_update_referral()
    {
        $referral = factory(Referral::class)->create();

        $response = $this->putJson("/api/referrals/{$referral->id}", ['referrer' => '']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['referrer']);
    }

    /** @test */
    public function referrer_must_be_a_valid_format_to_update_referral()
    {
        $referral = factory(Referral::class)->create();

        $response = $this->putJson("/api/referrals/{$referral->id}", ['referrer' => 'invalid']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['referrer']);
    }

    /** @test */
    public function referrer_name_must_not_be_empty_to_update_referral()
    {
        $referral = factory(Referral::class)->create();

        $response = $this->putJson("/api/referrals/{$referral->id}", ['referrer' => ['name' => '']]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['referrer.name']);
    }

    /** @test */
    public function referrer_title_must_not_be_empty_to_update_referral()
    {
        $referral = factory(Referral::class)->create();

        $response = $this->putJson("/api/referrals/{$referral->id}", ['referrer' => ['title' => '']]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['referrer.title']);
    }

    /** @test */
    public function referrer_phone_must_not_be_empty_to_update_referral()
    {
        $referral = factory(Referral::class)->create();

        $response = $this->putJson("/api/referrals/{$referral->id}", ['referrer' => ['phone' => '']]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['referrer.phone']);
    }
}
