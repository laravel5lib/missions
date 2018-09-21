<?php

namespace Tests\Feature\Api\Requirements;

use Tests\TestCase;
use App\Models\v1\Campaign;
use App\Models\v1\Requirement;
use App\Models\v1\Reservation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateRequirementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function update_campaign_requirement_and_save_changes_in_storage()
    {
        $campaign = factory(Campaign::class)->create();

        $requirement = factory(Requirement::class)->create([
            'requester_id' => $campaign->id, 
            'requester_type' => 'campaigns',
            'name' => 'Passport',
            'short_desc' => 'Test description.',
            'document_type' => 'passport',
            'due_at' => today()->addYear()
        ]);

        $campaign->requireables()->attach($requirement->id);

        $response = $this->putJson("/api/campaigns/{$campaign->id}/requirements/{$requirement->id}", [
            'name' => 'Passport Book',
            'short_desc' => 'Updated description.',
            'due_at' => today()->addMonths(6)->toDateTimeString()
        ])->assertStatus(200)
        ->assertJson([
            'data' => [
                'id' => $requirement->id,
                'name' => 'Passport Book',
                'short_desc' => 'Updated description.',
                'due_at' => today()->addMonths(6)->toIso8601String()
            ]
        ]);

        $this->assertDatabaseHas('requirements', [
            'id' => $requirement->id, 
            'name' => 'Passport Book', 
            'short_desc' => 'Updated description.',
            'due_at' => today()->addMonths(6)->toIso8601String()
        ]);
    }

    /** @test */
    public function update_requirement_as_upfront()
    {
        $campaign = factory(Campaign::class)->create();

        $requirement = factory(Requirement::class)->create([
            'requester_id' => $campaign->id, 
            'requester_type' => 'campaigns',
            'due_at' => today()->addYear(),
            'upfront' => false
        ]);

        $campaign->requireables()->attach($requirement->id);

        $this->putJson("/api/campaigns/{$campaign->id}/requirements/{$requirement->id}", [
            'upfront' => true
        ])
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                'id' => $requirement->id,
                'due_at' => null,
                'upfront' => true
            ]
        ]);

    }

    /** @test */
    public function requirement_name_cannot_be_null_or_blank_to_update_requirement()
    {
        $campaign = factory(Campaign::class)->create();

        $requirement = factory(Requirement::class)->create();

        $campaign->requireables()->attach($requirement->id);

        $changes = [
            'name' => ''
        ];

        $this->putJson("/api/campaigns/{$campaign->id}/requirements/{$requirement->id}", $changes)
             ->assertStatus(422)
             ->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function requirement_name_must_be_unique_to_the_campaign_to_update_requirement()
    {
        $campaign = factory(Campaign::class)->create();

        factory(Requirement::class)->create([
            'name' => 'Passport', 'requester_id' => $campaign->id, 'requester_type' => 'campaigns'
        ]);

        $requirement = factory(Requirement::class)->create([
            'name' => 'Visa', 'requester_id' => $campaign->id, 'requester_type' => 'campaigns'
        ]);

        $campaign->requireables()->attach($requirement->id);

        $changes = [
            'name' => 'Passport'
        ];

        $this->putJson("/api/campaigns/{$campaign->id}/requirements/{$requirement->id}", $changes)
             ->assertStatus(422)
             ->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function document_type_cannot_be_null_or_blank_to_update_requirement()
    {
        $campaign = factory(Campaign::class)->create();
        
        $requirement = factory(Requirement::class)->create();

        $campaign->requireables()->attach($requirement->id);

        $changes = [
            'document_type' => ''
        ];

        $this->putJson("/api/campaigns/{$campaign->id}/requirements/{$requirement->id}", $changes)
             ->assertStatus(422)
             ->assertJsonValidationErrors(['document_type']);
    }

    /** @test */
    public function document_due_at_must_be_a_valid_date_to_update_requirement()
    {
        $campaign = factory(Campaign::class)->create();
        
        $requirement = factory(Requirement::class)->create();

        $campaign->requireables()->attach($requirement->id);

        $changes = [
            'due_at' => 'invalid'
        ];

        $this->putJson("/api/campaigns/{$campaign->id}/requirements/{$requirement->id}", $changes)
             ->assertStatus(422)
             ->assertJsonValidationErrors(['due_at']);
    }

    /** @test */
    public function change_requirement_status_for_reservation()
    {
        $requirement = factory(Requirement::class)->create();
        $reservation = factory(Reservation::class)->create(['trip_id' => $requirement->requester_id]);
        $reservation->requireables()->attach($requirement->id, ['status' => 'incomplete']);

        $this->putJson("/api/reservations/{$reservation->id}/requirements/{$requirement->id}", ['status' => 'completed'])
             ->assertStatus(200)
             ->assertJson(['data' => ['status' => 'completed']]);

        $this->assertDatabaseHas(
            'requireables', 
            [
                'requirement_id' => $requirement->id, 
                'requireable_type' => 'reservations', 
                'requireable_id' => $reservation->id, 
                'status' => 'completed'
            ]
        );
    }
}
