<?php

namespace Tests\Feature\Api\Essays;

use Tests\TestCase;
use App\Models\v1\User;
use App\Models\v1\Essay;
use App\Models\v1\Reservation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateEssayTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /** @test */
    public function update_testimony_and_save_changes_in_storage()
    {
        $user = factory(User::class)->create();
        $testimony = factory(Essay::class)->create(['author_name' => 'John Doe']);

        $response = $this->putJson("/api/essays/{$testimony->id}", [
            'user_id' => $user->id,
            'author_name' => 'James Smith', 
            'content' => ['foo' => 'bar']
        ]);

        $response->assertOk()
                 ->assertJson([
                    'data' => [
                        'id' => $testimony->id,
                        'user_id' => $user->id,
                        'subject' => 'Testimony',
                        'author_name' => 'James Smith',
                        'content' => ['foo' => 'bar']
                    ]
                 ]);

        $this->assertDatabaseHas('essays', [
            'id' => $testimony->id, 
            'user_id' => $user->id, 
            'subject' => 'Testimony', 
            'author_name' => 'James Smith',
            'content' => castToJson(['foo' => 'bar'])
        ]);
    }

    /** @test */
    public function attach_testimony_to_reservation_when_updated()
    {
        $reservation = factory(Reservation::class)->create();

        $essay = factory(Essay::class)->create();

        $response = $this->putJson("/api/essays/{$essay->id}", ['reservation_id' => $reservation->id]);

        $response->assertOk();

        $this->assertDatabaseHas(
            'reservation_documents', 
            ['reservation_id' => $reservation->id, 'documentable_type' => 'essays']
        );
    }

    /** @test */
    public function attach_influencer_application_to_reservation_when_updated()
    {
        $reservation = factory(Reservation::class)->create();

        $essay = factory(Essay::class)->create(['subject' => 'Influencer']);

        $response = $this->putJson("/api/influencer-applications/{$essay->id}", ['reservation_id' => $reservation->id]);

        $response->assertOk();

        $this->assertDatabaseHas(
            'reservation_documents', 
            ['reservation_id' => $reservation->id, 'documentable_type' => 'influencer_applications']
        );
    }

    /** @test */
    public function author_name_must_not_be_empty_to_update_essay()
    {
        $this->putJson("/api/essays/{$this->faker->uuid}", ['author_name' => ''])
             ->assertStatus(422)
             ->assertJsonValidationErrors(['author_name']);
    }

    /** @test */
    public function user_id_must_not_be_empty_to_update_essay()
    {
        $this->putJson("/api/essays/{$this->faker->uuid}", ['user_id' => ''])
             ->assertStatus(422)
             ->assertJsonValidationErrors(['user_id']);
    }

    /** @test */
    public function user_id_must_be_valid_to_update_essay()
    {
        $this->putJson("/api/essays/{$this->faker->uuid}", ['user_id' => 'invalid'])
             ->assertStatus(422)
             ->assertJsonValidationErrors(['user_id']);
    }

    /** @test */
    public function content_must_not_be_empty_to_update_essay()
    {
        $this->putJson("/api/essays/{$this->faker->uuid}", ['content' => ''])
             ->assertStatus(422)
             ->assertJsonValidationErrors(['content']);
    }

    /** @test */
    public function content_must_be_in_array_format_to_update_essay()
    {
        $this->putJson("/api/essays/{$this->faker->uuid}", ['content' => 'invalid'])
             ->assertStatus(422)
             ->assertJsonValidationErrors(['content']);
    }
}
