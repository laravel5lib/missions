<?php

namespace Tests\Feature\Api\Passports;

use Tests\TestCase;
use App\Models\v1\Passport;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdatePassportTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function update_passport_and_save_changes_in_storage()
    {
        $passport = factory(Passport::class)->create(['given_names' => 'John Doe']);

        $response = $this->putJson("/api/passports/{$passport->id}", [
            'given_names' => 'James Smith'
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                    'data' => [
                        'id' => $passport->id,
                        'given_names' => 'James Smith'
                    ]
                 ]);

        $this->assertDatabaseHas('passports', ['id' => $passport->id, 'given_names' => 'James Smith']);
    }

    /** @test */
    public function given_names_is_required_to_update_passport()
    {
        $passport = factory(Passport::class)->create();

        $response = $this->putJson("/api/passports/{$passport->id}", [
            'given_names' => ''
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['given_names']);
    }

    /** @test */
    public function surname_is_required_to_update_passport()
    {
        $passport = factory(Passport::class)->create();

        $response = $this->putJson("/api/passports/{$passport->id}", [
            'surname' => ''
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['surname']);
    }

    /** @test */
    public function number_is_required_to_update_passport()
    {
        $passport = factory(Passport::class)->create();

        $response = $this->putJson("/api/passports/{$passport->id}", [
            'number' => ''
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['number']);
    }

    /** @test */
    public function expires_at_is_required_to_update_passport()
    {
        $passport = factory(Passport::class)->create();

        $response = $this->putJson("/api/passports/{$passport->id}", [
            'expires_at' => ''
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['expires_at']);
    }

    /** @test */
    public function expires_at_is_a_valid_date_to_update_passport()
    {
        $passport = factory(Passport::class)->create();

        $response = $this->putJson("/api/passports/{$passport->id}", [
            'expires_at' => 'invalid'
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['expires_at']);
    }

    /** @test */
    public function expires_at_is_greater_than_now_to_update_passport()
    {
        $passport = factory(Passport::class)->create();

        $response = $this->putJson("/api/passports/{$passport->id}", [
            'expires_at' => now()->subDay(1)->toDateString()
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['expires_at']);
    }

    /** @test */
    public function birth_country_is_required_to_update_passport()
    {
        $passport = factory(Passport::class)->create();

        $response = $this->putJson("/api/passports/{$passport->id}", [
            'birth_country' => ''
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['birth_country']);
    }

    /** @test */
    public function citizenship_is_required_to_update_passport()
    {
        $passport = factory(Passport::class)->create();

        $response = $this->putJson("/api/passports/{$passport->id}", [
            'citizenship' => ''
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['citizenship']);
    }

    /** @test */
    public function user_id_is_required_to_update_passport()
    {
        $passport = factory(Passport::class)->create();

        $response = $this->putJson("/api/passports/{$passport->id}", [
            'user_id' => ''
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['user_id']);
    }

    /** @test */
    public function user_id_is_valid_to_update_passport()
    {
        $passport = factory(Passport::class)->create();

        $response = $this->putJson("/api/passports/{$passport->id}", [
            'user_id' => 'invalid'
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['user_id']);
    }
}
