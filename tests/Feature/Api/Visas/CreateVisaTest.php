<?php

namespace Tests\Feature\Api\Visas;

use Tests\TestCase;
use App\Models\v1\User;
use App\Models\v1\Reservation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateVisaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function create_new_visa_and_save_in_storage()
    {
        $data = $this->formData();

        $response = $this->postJson('/api/visas', $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('visas', $data);
    }

    /** @test */
    public function attach_new_visa_to_reservation_when_created()
    {
        $reservation = factory(Reservation::class)->create();

        $data = $this->formData(['reservation_id' => $reservation->id]);

        $response = $this->postJson('/api/visas', $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('reservation_documents', ['reservation_id' => $reservation->id]);
    }

    /** @test */
    public function user_id_is_required_to_create_visa()
    {
        $data = $this->formData(['user_id' => '']);

        $response = $this->postJson('/api/visas', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['user_id']);
    }

    /** @test */
    public function user_id_must_be_valid_to_create_visa()
    {
        $data = $this->formData(['user_id' => 'invalid']);

        $response = $this->postJson('/api/visas', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['user_id']);
    }

    /** @test */
    public function number_is_required_to_create_visa()
    {
        $data = $this->formData(['number' => '']);

        $response = $this->postJson('/api/visas', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['number']);
    }

    /** @test */
    public function number_must_be_at_least_6_characters_long_to_create_visa()
    {
        $data = $this->formData(['number' => '12345']);

        $response = $this->postJson('/api/visas', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['number']);
    }

    /** @test */
    public function country_code_is_required_to_create_visa()
    {
        $data = $this->formData(['country_code' => '']);

        $response = $this->postJson('/api/visas', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['country_code']);
    }

    /** @test */
    public function must_be_a_valid_country_code_to_create_visa()
    {
        $data = $this->formData(['country_code' => 'invalid']);

        $response = $this->postJson('/api/visas', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['country_code']);
    }

    /** @test */
    public function issued_at_date_is_required_to_create_visa()
    {
        $data = $this->formData(['issued_at' => '']);

        $response = $this->postJson('/api/visas', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['issued_at']);
    }

    /** @test */
    public function issued_at_must_be_a_valid_date_to_create_visa()
    {
        $data = $this->formData(['issued_at' => 'invalid']);

        $response = $this->postJson('/api/visas', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['issued_at']);
    }

    /** @test */
    public function issued_at_must_be_a_date_in_the_past_to_create_visa()
    {
        $data = $this->formData(['issued_at' => now()->addDay()->toDateString()]);

        $response = $this->postJson('/api/visas', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['issued_at']);
    }

    /** @test */
    public function expires_at_date_is_required_to_create_visa()
    {
        $data = $this->formData(['expires_at' => '']);

        $response = $this->postJson('/api/visas', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['expires_at']);
    }

    /** @test */
    public function expires_at_must_be_a_valid_date_to_create_visa()
    {
        $data = $this->formData(['expires_at' => 'invalid']);

        $response = $this->postJson('/api/visas', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['expires_at']);
    }

    /** @test */
    public function expires_at_must_be_a_future_date_to_create_visa()
    {
        $data = $this->formData(['expires_at' => now()->subDay()->toDateString()]);

        $response = $this->postJson('/api/visas', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['expires_at']);
    }

    private function formData($data = [])
    {
        $array = [
            'given_names' => 'John Michael',
            'surname' => 'Doe',
            'number' => 'abc1234567',
            'country_code' => 'in',
            'issued_at' => now()->subMonth()->toDateString(),
            'expires_at' => now()->addYear()->toDateString(),
            'user_id' => factory(User::class)->create()->id
        ];

        return array_merge($array, $data);
    }
}
