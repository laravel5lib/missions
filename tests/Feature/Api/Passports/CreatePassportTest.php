<?php

namespace Tests\Feature\Api\Passports;

use Tests\TestCase;
use App\Models\v1\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreatePassportTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function create_new_passport_and_save_in_storage()
    {
        $data = $this->getFormData();

        $response = $this->postJson('/api/passports', $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('passports', $data);
    }

    /** @test */
    public function given_names_is_required_to_create_passport()
    {
        $data = array_except($this->getFormData(), ['given_names']);

        $response = $this->postJson('/api/passports', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['given_names']);
    }

    /** @test */
    public function surname_is_required_to_create_passport()
    {
        $data = array_except($this->getFormData(), ['surname']);

        $response = $this->postJson('/api/passports', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['surname']);
    }

    /** @test */
    public function number_is_required_to_create_passport()
    {
        $data = array_except($this->getFormData(), ['number']);

        $response = $this->postJson('/api/passports', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['number']);
    }

    /** @test */
    public function expires_at_is_required_to_create_passport()
    {
        $data = array_except($this->getFormData(), ['expires_at']);

        $response = $this->postJson('/api/passports', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['expires_at']);
    }

    /** @test */
    public function expires_at_is_a_valid_date_to_create_passport()
    {
        $data = array_merge($this->getFormData(), ['expires_at' => 'invalid']);

        $response = $this->postJson('/api/passports', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['expires_at']);
    }

    /** @test */
    public function expires_at_is_greater_than_now_to_create_passport()
    {
        $data = array_merge($this->getFormData(), ['expires_at' => now()->subDay(1)->toDateString()]);

        $response = $this->postJson('/api/passports', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['expires_at']);
    }

    /** @test */
    public function birth_country_is_required_to_create_passport()
    {
        $data = array_except($this->getFormData(), ['birth_country']);

        $response = $this->postJson('/api/passports', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['birth_country']);
    }

    /** @test */
    public function citizenship_is_required_to_create_passport()
    {
        $data = array_except($this->getFormData(), ['citizenship']);

        $response = $this->postJson('/api/passports', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['citizenship']);
    }

    /** @test */
    public function user_id_is_required_to_create_passport()
    {
        $data = array_except($this->getFormData(), ['user_id']);

        $response = $this->postJson('/api/passports', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['user_id']);
    }

    /** @test */
    public function user_id_is_valid_to_create_passport()
    {
        $data = array_merge($this->getFormData(), ['user_id' => 'invalid']);

        $response = $this->postJson('/api/passports', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['user_id']);
    }

    /**
     * Build an array of form data.
     * 
     * @return array
     */
    private function getFormData()
    {
        return [
            'given_names' => 'John Michael',
            'surname' => 'Doe',
            'number' => 'ABC123',
            'expires_at' => now()->addYears(5)->toDateString(),
            'birth_country' => 'us',
            'citizenship' => 'us',
            'user_id' => factory(User::class)->create()->id
        ];
    }
}
