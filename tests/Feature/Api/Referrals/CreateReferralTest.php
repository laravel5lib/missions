<?php

namespace Tests\Feature\Api\Referrals;

use Tests\TestCase;
use App\Models\v1\User;
use App\Models\v1\Reservation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateReferralTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->withoutJobs();
    }

    /** @test */
    public function create_a_new_referral_and_save_in_storage()
    {
        $data = $this->formData();

        $response = $this->postJson('/api/referrals', $data);

        $response->assertStatus(201);

        $tableData = array_merge(
            array_except($data, 'referrer'),
            [ 'referrer' => castToJson($data['referrer']) ]
        );

        $this->assertDatabaseHas('referrals', $tableData);
    }

    /** @test */
    public function attach_new_referral_to_reservation_when_created()
    {
        $reservation = factory(Reservation::class)->create();

        $data = $this->formData(['reservation_id' => $reservation->id]);

        $response = $this->postJson('/api/referrals', $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas(
            'reservation_documents', 
            ['reservation_id' => $reservation->id, 'documentable_type' => 'referrals']
        );
    }

    /** @test */
    public function user_id_is_required_to_create_a_referral()
    {
        $data = $this->formData(['user_id' => '']);

        $response = $this->postJson('/api/referrals', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['user_id']);
    }

    /** @test */
    public function user_id_must_be_valid_to_create_a_referral()
    {
        $data = $this->formData(['user_id' => 'invalid']);

        $response = $this->postJson('/api/referrals', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['user_id']);
    }

    /** @test */
    public function applicant_name_is_required_to_create_a_referral()
    {
        $data = $this->formData(['applicant_name' => '']);

        $response = $this->postJson('/api/referrals', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['applicant_name']);
    }

    /** @test */
    public function type_is_required_to_create_a_referral()
    {
        $data = $this->formData(['type' => '']);

        $response = $this->postJson('/api/referrals', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['type']);
    }

    /** @test */
    public function attention_to_is_required_to_create_a_referral()
    {
        $data = $this->formData(['attention_to' => '']);

        $response = $this->postJson('/api/referrals', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['attention_to']);
    }

    /** @test */
    public function recipient_email_is_required_to_create_a_referral()
    {
        $data = $this->formData(['recipient_email' => '']);

        $response = $this->postJson('/api/referrals', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['recipient_email']);
    }

    /** @test */
    public function recipient_email_must_be_valid_to_create_a_referral()
    {
        $data = $this->formData(['recipient_email' => 'invalid']);

        $response = $this->postJson('/api/referrals', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['recipient_email']);
    }

    /** @test */
    public function referrer_is_required_to_create_a_referral()
    {
        $data = $this->formData(['referrer' => '']);

        $response = $this->postJson('/api/referrals', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['referrer']);
    }

    /** @test */
    public function referrer_must_be_a_valid_format_to_create_a_referral()
    {
        $data = $this->formData(['referrer' => 'invalid']);

        $response = $this->postJson('/api/referrals', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['referrer']);
    }

    /** @test */
    public function referrer_name_is_required_to_create_a_referral()
    {
        $data = $this->formData(['referrer' => ['name' => '']]);

        $response = $this->postJson('/api/referrals', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['referrer.name']);
    }

    /** @test */
    public function referrer_title_is_required_to_create_a_referral()
    {
        $data = $this->formData(['referrer' => ['title' => '']]);

        $response = $this->postJson('/api/referrals', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['referrer.title']);
    }

    /** @test */
    public function referrer_phone_is_required_to_create_a_referral()
    {
        $data = $this->formData(['referrer' => ['phone' => '']]);

        $response = $this->postJson('/api/referrals', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['referrer.phone']);
    }

    private function formData($data = [])
    {
        $array = [
            'user_id' => factory(User::class)->create()->id,
            'applicant_name' => 'Luke Skywalker',
            'attention_to' => 'Ben Kenobi',
            'recipient_email' => 'ben@gmail.com',
            'type' => 'pastoral',
            'referrer' => [
                'title' => 'Jedi Master',
                'name' => 'Ben Kenobi',
                'phone' => '1234567890'
            ]
        ];

        return array_merge($array, $data);
    }
}
