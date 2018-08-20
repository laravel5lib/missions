<?php

namespace Tests\Feature\Api\Essays;

use Tests\TestCase;
use App\Models\v1\User;
use App\Models\v1\Reservation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateEssayTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function create_a_new_testimony_and_save_in_storage()
    {
        $data = $this->formData();

        $response = $this->postJson('/api/essays', $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('essays', [
            'subject' => 'Testimony', 
            'author_name' => 'John Doe', 
            'user_id' => $data['user_id'],
            'content' => castToJson($data['content'])
        ]);
    }

    /** @test */
    public function create_a_new_influencer_application_and_save_in_storage()
    {
        $data = $this->formData();

        $response = $this->postJson('/api/influencer-applications', $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('essays', [
            'subject' => 'Influencer', 
            'author_name' => 'John Doe', 
            'user_id' => $data['user_id'],
            'content' => castToJson($data['content'])
        ]);
    }

    /** @test */
    public function attach_new_testimony_to_reservation_when_created()
    {
        $reservation = factory(Reservation::class)->create();

        $data = $this->formData(['reservation_id' => $reservation->id]);

        $response = $this->postJson('/api/essays', $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas(
            'reservation_documents', 
            ['reservation_id' => $reservation->id, 'documentable_type' => 'essays']
        );
    }

    /** @test */
    public function attach_new_influencer_application_to_reservation_when_created()
    {
        $reservation = factory(Reservation::class)->create();

        $data = $this->formData(['reservation_id' => $reservation->id]);

        $response = $this->postJson('/api/influencer-applications', $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas(
            'reservation_documents', 
            ['reservation_id' => $reservation->id, 'documentable_type' => 'essays']
        );
    }

    /** @test */
    public function an_author_name_is_required_to_create_a_new_essay()
    {
        $this->postJson('/api/essays', ['author_name' => ''])
             ->assertStatus(422)
             ->assertJsonValidationErrors(['author_name']);
    }

    /** @test */
    public function a_user_id_is_required_to_create_a_new_essay()
    {
        $this->postJson('/api/essays', ['user_id' => ''])
             ->assertStatus(422)
             ->assertJsonValidationErrors(['user_id']);
    }

    /** @test */
    public function a_valid_user_id_is_required_to_create_a_new_essay()
    {
        $this->postJson('/api/essays', ['user_id' => 'invalid'])
             ->assertStatus(422)
             ->assertJsonValidationErrors(['user_id']);
    }

    /** @test */
    public function content_is_required_to_create_a_new_essay()
    {
        $this->postJson('/api/essays', ['content' => ''])
             ->assertStatus(422)
             ->assertJsonValidationErrors(['content']);
    }

    /** @test */
    public function content_must_be_in_array_format_to_create_a_new_essay()
    {
        $this->postJson('/api/essays', ['content' => 'invalid'])
             ->assertStatus(422)
             ->assertJsonValidationErrors(['content']);
    }

    private function formData($data = [])
    {
        $array = [
            'author_name' => 'John Doe',
            'user_id' => factory(User::class)->create(['avatar_upload_id' => null, 'banner_upload_id' => null])->id,
            'content' => [
                ['q' => 'Describe how you decided to follow Christ', 'a' => 'test'],
                ['q' => 'Describe your church involvement', 'a' => 'test'],
                ['q' => 'Describe your current walk with God', 'a' => 'test'],
                ['q' => 'Please describe any prior missions trip experience you have', 'a' => 'test'],
            ]
        ];

        return array_merge($array, $data);
    }
}
