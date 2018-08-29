<?php

namespace Tests\Feature\Api\Visas;

use Tests\TestCase;
use App\Models\v1\Visa;
use App\Models\v1\Reservation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateVisaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function update_visa_and_save_changes_in_storage()
    {
        $visa = factory(Visa::class)->create(['given_names' => 'John Michael', 'surname' => 'Doe']);

        $response = $this->putJson("/api/visas/{$visa->id}", ['given_names' => 'James Smith']);

        $response->assertOk()
                 ->assertJson(['data' => ['given_names' => 'James Smith', 'surname' => 'Doe']]);

        $this->assertDatabaseHas('visas', ['id' => $visa->id, 'given_names' => 'James Smith']);
    }

    /** @test */
    public function attach_visa_to_reservation_when_updated()
    {
        $reservation = factory(Reservation::class)->create();

        $visa = factory(Visa::class)->create([
            'given_names' => 'John Doe', 
        ]);

        $response = $this->putJson("/api/visas/{$visa->id}", [
            'given_names' => 'James Smith',
            'reservation_id' => $reservation->id
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('reservation_documents', ['reservation_id' => $reservation->id]);
    }

    /** @test */
    public function user_id_must_not_be_empty_to_update_visa()
    {
        $visa = factory(Visa::class)->create();

        $response = $this->putJson("/api/visas/{$visa->id}", ['user_id' => '']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['user_id']);
    }

    /** @test */
    public function user_id_must_be_valid_to_update_visa()
    {
        $visa = factory(Visa::class)->create();

        $response = $this->putJson("/api/visas/{$visa->id}", ['user_id' => 'invalid']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['user_id']);
    }

    /** @test */
    public function given_names_must_not_be_empty_to_update_visa()
    {
        $visa = factory(Visa::class)->create();

        $response = $this->putJson("/api/visas/{$visa->id}", ['given_names' => '']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['given_names']);
    }

    /** @test */
    public function surname_must_not_be_empty_to_update_visa()
    {
        $visa = factory(Visa::class)->create();

        $response = $this->putJson("/api/visas/{$visa->id}", ['surname' => '']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['surname']);
    }

    /** @test */
    public function number_must_not_be_empty_to_update_visa()
    {
        $visa = factory(Visa::class)->create();

        $response = $this->putJson("/api/visas/{$visa->id}", ['number' => '']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['number']);
    }

    /** @test */
    public function number_must_be_at_least_6_characters_to_update_visa()
    {
        $visa = factory(Visa::class)->create();

        $response = $this->putJson("/api/visas/{$visa->id}", ['number' => '12345']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['number']);
    }

    /** @test */
    public function country_code_must_not_be_empty_to_update_visa()
    {
        $visa = factory(Visa::class)->create();

        $response = $this->putJson("/api/visas/{$visa->id}", ['country_code' => '']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['country_code']);
    }

    /** @test */
    public function country_code_must_be_a_valid_country_to_update_visa()
    {
        $visa = factory(Visa::class)->create();

        $response = $this->putJson("/api/visas/{$visa->id}", ['country_code' => 'invalid']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['country_code']);
    }

    /** @test */
    public function issued_at_must_not_be_empty_to_update_visa()
    {
        $visa = factory(Visa::class)->create();

        $response = $this->putJson("/api/visas/{$visa->id}", ['issued_at' => '']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['issued_at']);
    }

    /** @test */
    public function issued_at_must_be_a_valid_date_to_update_visa()
    {
        $visa = factory(Visa::class)->create();

        $response = $this->putJson("/api/visas/{$visa->id}", ['issued_at' => 'invalid']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['issued_at']);
    }

    /** @test */
    public function issued_at_must_be_a_date_in_the_past_to_update_visa()
    {
        $visa = factory(Visa::class)->create();

        $response = $this->putJson("/api/visas/{$visa->id}", ['issued_at' => now()->addDay()->toDateTimeString()]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['issued_at']);
    }

    /** @test */
    public function expires_at_must_not_be_empty_to_update_visa()
    {
        $visa = factory(Visa::class)->create();

        $response = $this->putJson("/api/visas/{$visa->id}", ['expires_at' => '']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['expires_at']);
    }

    /** @test */
    public function expires_at_must_be_a_valid_date_to_update_visa()
    {
        $visa = factory(Visa::class)->create();

        $response = $this->putJson("/api/visas/{$visa->id}", ['expires_at' => 'invalid']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['expires_at']);
    }

    /** @test */
    public function expires_at_must_be_a_future_date_update_visa()
    {
        $visa = factory(Visa::class)->create();

        $response = $this->putJson("/api/visas/{$visa->id}", ['expires_at' => now()->subDay()->toDateTimeString()]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['expires_at']);
    }
}
