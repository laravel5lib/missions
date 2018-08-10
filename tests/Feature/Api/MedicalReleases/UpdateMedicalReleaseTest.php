<?php

namespace Tests\Feature\Api\MedicalReleases;

use Tests\TestCase;
use App\Models\v1\Reservation;
use App\Models\v1\MedicalRelease;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateMedicalReleaseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function update_medical_release_and_save_changes_in_storage()
    {
        $release = factory(MedicalRelease::class)->create([
            'name' => 'John Doe', 
        ]);
        $release->syncConditions([['name' => 'Asthma']]);
        $release->syncAllergies([['name' => 'Peanuts']]);

        $response = $this->putJson("/api/medical-releases/{$release->id}", [
            'name' => 'James Smith',
            'conditions' => [
                ['name' => 'Back Pain']
            ],
            'allergies' => [
                ['name' => 'Peanuts'], 
                ['name' => 'Wheat']
            ]
        ]);

        $response->assertOK()
                 ->assertJson([
                    'data' => [
                        'name' => 'James Smith',
                        'conditions' => [['name' => 'Back Pain']],
                        'allergies' => [['name' => 'Peanuts'], ['name' => 'Wheat']]
                    ]
                 ]);

        $this->assertDatabaseHas('medical_releases', ['id' => $release->id, 'name' => 'James Smith']);
        $this->assertDatabaseHas('medical_conditions', ['name' => 'Back Pain']);
        $this->assertDatabaseHas('medical_allergies', ['name' => 'Peanuts']);
        $this->assertDatabaseHas('medical_allergies', ['name' => 'Wheat']);
    }

     /** @test */
    public function attach_medical_release_to_reservation_when_updated()
    {
        $reservation = factory(Reservation::class)->create();

        $release = factory(MedicalRelease::class)->create([
            'name' => 'John Doe', 
        ]);

        $response = $this->putJson("/api/medical-releases/{$release->id}", [
            'name' => 'James Smith',
            'reservation_id' => $reservation->id
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('reservation_documents', ['reservation_id' => $reservation->id]);
    }

    /** @test */
    public function user_id_cannot_be_empty_to_update_medical_release()
    {
        $release = factory(MedicalRelease::class)->create();

        $response = $this->putJson("/api/medical-releases/{$release->id}", ['user_id' => '']);

        $response->assertStatus(422)->assertJsonValidationErrors(['user_id']);
    }

     /** @test */
    public function user_id_must_be_valid_to_update_medical_release()
    {
        $release = factory(MedicalRelease::class)->create();

        $response = $this->putJson("/api/medical-releases/{$release->id}", ['user_id' => 'invalid']);

        $response->assertStatus(422)->assertJsonValidationErrors(['user_id']);
    }

     /** @test */
    public function name_cannot_be_empty_to_update_medical_release()
    {
        $release = factory(MedicalRelease::class)->create();

        $response = $this->putJson("/api/medical-releases/{$release->id}", ['name' => '']);

        $response->assertStatus(422)->assertJsonValidationErrors(['name']);
    }

     /** @test */
    public function ins_provider_cannot_be_empty_when_ins_policy_no_is_present_to_update_medical_release()
    {
        $release = factory(MedicalRelease::class)->create();

        $response = $this->putJson("/api/medical-releases/{$release->id}", [
            'ins_provider' => '',
            'ins_policy_no' => 'ABC123'
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['ins_provider']);
    }

    /** @test */
    public function ins_policy_no_cannot_be_empty_when_ins_provider_is_present_to_update_medical_release()
    {
        $release = factory(MedicalRelease::class)->create();

        $response = $this->putJson("/api/medical-releases/{$release->id}", [
            'ins_provider' => 'Blue Cross',
            'ins_policy_no' => ''
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['ins_policy_no']);
    }

     /** @test */
    public function takes_medication_cannot_be_empty_to_update_medical_release()
    {
        $release = factory(MedicalRelease::class)->create();

        $response = $this->putJson("/api/medical-releases/{$release->id}", ['takes_medication' => '']);

        $response->assertStatus(422)->assertJsonValidationErrors(['takes_medication']);
    }

     /** @test */
    public function takes_medication_must_be_valid_to_update_medical_release()
    {
        $release = factory(MedicalRelease::class)->create();

        $response = $this->putJson("/api/medical-releases/{$release->id}", ['takes_medication' => 'invalid']);

        $response->assertStatus(422)->assertJsonValidationErrors(['takes_medication']);
    }

     /** @test */
    public function conditions_must_be_a_valid_format_to_update_medical_release()
    {
        $release = factory(MedicalRelease::class)->create();

        $response = $this->putJson("/api/medical-releases/{$release->id}", ['conditions' => 'invalid']);

        $response->assertStatus(422)->assertJsonValidationErrors(['conditions']);
    }

     /** @test */
    public function a_conditions_name_cannot_be_empty_to_update_medical_release()
    {
        $release = factory(MedicalRelease::class)->create();

        $response = $this->putJson("/api/medical-releases/{$release->id}", ['conditions' => [['name' => '']]]);

        $response->assertStatus(422)->assertJsonValidationErrors(['conditions.0.name']);
    }

    /** @test */
    public function allergies_must_be_a_valid_format_to_update_medical_release()
    {
        $release = factory(MedicalRelease::class)->create();

        $response = $this->putJson("/api/medical-releases/{$release->id}", ['allergies' => 'invalid']);

        $response->assertStatus(422)->assertJsonValidationErrors(['allergies']);
    }

     /** @test */
    public function a_allergies_name_cannot_be_empty_to_update_medical_release()
    {
        $release = factory(MedicalRelease::class)->create();

        $response = $this->putJson("/api/medical-releases/{$release->id}", ['allergies' => [['name' => '']]]);

        $response->assertStatus(422)->assertJsonValidationErrors(['allergies.0.name']);
    }

     /** @test */
    public function emergency_contact_cannot_be_empty_to_update_medical_release()
    {
        $release = factory(MedicalRelease::class)->create();

        $response = $this->putJson("/api/medical-releases/{$release->id}", ['emergency_contact' => '']);

        $response->assertStatus(422)->assertJsonValidationErrors(['emergency_contact']);
    }

     /** @test */
    public function emergency_contact_must_be_in_a_valid_format_to_update_medical_release()
    {
        $release = factory(MedicalRelease::class)->create();

        $response = $this->putJson("/api/medical-releases/{$release->id}", ['emergency_contact' => 'invalid']);

        $response->assertStatus(422)->assertJsonValidationErrors(['emergency_contact']);
    }

     /** @test */
    public function emergency_contact_name_cannot_be_empty_to_update_medical_release()
    {
        $release = factory(MedicalRelease::class)->create();

        $response = $this->putJson("/api/medical-releases/{$release->id}", ['emergency_contact' => ['name' => '']]);

        $response->assertStatus(422)->assertJsonValidationErrors(['emergency_contact.name']);
    }

     /** @test */
    public function emergency_contact_email_cannot_be_empty_to_update_medical_release()
    {
        $release = factory(MedicalRelease::class)->create();

        $response = $this->putJson("/api/medical-releases/{$release->id}", ['emergency_contact' => ['email' => '']]);

        $response->assertStatus(422)->assertJsonValidationErrors(['emergency_contact.email']);
    }

     /** @test */
    public function emergency_contact_email_must_be_valid_to_update_medical_release()
    {
        $release = factory(MedicalRelease::class)->create();

        $response = $this->putJson("/api/medical-releases/{$release->id}", ['emergency_contact' => ['email' => 'invalid']]);

        $response->assertStatus(422)->assertJsonValidationErrors(['emergency_contact.email']);
    }

     /** @test */
    public function emergency_contact_phone_cannot_be_empty_to_update_medical_release()
    {
        $release = factory(MedicalRelease::class)->create();

        $response = $this->putJson("/api/medical-releases/{$release->id}", ['emergency_contact' => ['phone' => '']]);

        $response->assertStatus(422)->assertJsonValidationErrors(['emergency_contact.phone']);
    }

     /** @test */
    public function emergency_contact_relationship_cannot_be_empty_to_update_medical_release()
    {
        $release = factory(MedicalRelease::class)->create();

        $response = $this->putJson("/api/medical-releases/{$release->id}", ['emergency_contact' => ['relationship' => '']]);

        $response->assertStatus(422)->assertJsonValidationErrors(['emergency_contact.relationship']);
    }

     /** @test */
    public function emergency_contact_relationship_must_be_valid_to_update_medical_release()
    {
        $release = factory(MedicalRelease::class)->create();

        $response = $this->putJson("/api/medical-releases/{$release->id}", ['emergency_contact' => ['relationship' => 'invalid']]);

        $response->assertStatus(422)->assertJsonValidationErrors(['emergency_contact.relationship']);
    }

     /** @test */
    public function pregnant_must_be_a_valid_format_to_update_medical_release()
    {
        $release = factory(MedicalRelease::class)->create();

        $response = $this->putJson("/api/medical-releases/{$release->id}", ['pregnant' => 'invalid']);

        $response->assertStatus(422)->assertJsonValidationErrors(['pregnant']);
    }

     /** @test */
    public function height_cannot_be_empty_to_update_medical_release()
    {
        $release = factory(MedicalRelease::class)->create();

        $response = $this->putJson("/api/medical-releases/{$release->id}", ['height' => '']);

        $response->assertStatus(422)->assertJsonValidationErrors(['height']);
    }

     /** @test */
    public function height_must_be_valid_to_update_medical_release()
    {
        $release = factory(MedicalRelease::class)->create();

        $response = $this->putJson("/api/medical-releases/{$release->id}", ['height' => 'invalid']);

        $response->assertStatus(422)->assertJsonValidationErrors(['height']);
    }

     /** @test */
    public function weight_cannot_be_empty_to_update_medical_release()
    {
        $release = factory(MedicalRelease::class)->create();

        $response = $this->putJson("/api/medical-releases/{$release->id}", ['weight' => '']);

        $response->assertStatus(422)->assertJsonValidationErrors(['weight']);
    }

     /** @test */
    public function weight_must_be_valid_to_update_medical_release()
    {
        $release = factory(MedicalRelease::class)->create();

        $response = $this->putJson("/api/medical-releases/{$release->id}", ['weight' => 'invalid']);

        $response->assertStatus(422)->assertJsonValidationErrors(['weight']);
    }
}
