<?php

namespace Tests\Feature\Api\MedicalReleases;

use Tests\TestCase;
use App\Models\v1\User;
use App\Models\v1\Reservation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateMedicalReleaseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function create_new_medical_release_and_save_in_storage()
    {
        $data = $this->getFormData();

        $response = $this->postJson('/api/medical-releases', $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas(
            'medical_releases', 
            array_merge(
                array_except($data, ['conditions', 'allergies']),
                [ 'is_risk' => 1, 'emergency_contact' => castToJson($data['emergency_contact']) ]
            )
        );

        $this->assertDatabaseHas('medical_conditions', ['name' => 'Asthma']);
        $this->assertDatabaseHas('medical_allergies', ['name' => 'Peanuts']);
    }

    /** @test */
    public function attach_new_medical_release_to_reservation_when_created()
    {
        $reservation = factory(Reservation::class)->create();

        $data = $this->getFormData(['reservation_id' => $reservation->id]);

        $response = $this->postJson('/api/medical-releases', $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas('reservation_documents', ['reservation_id' => $reservation->id]);
    }

    /** @test */
    public function user_id_is_required_to_create_a_new_medical_release()
    {
        $data = array_except($this->getFormData(), ['user_id']);

        $response = $this->postJson('/api/medical-releases', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['user_id']);
    }

    /** @test */
    public function user_id_must_be_valid_to_create_a_new_medical_release()
    {
        $data = $this->getFormData(['user_id' => 'invalid']);

        $response = $this->postJson('/api/medical-releases', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['user_id']);
    }

    /** @test */
    public function name_is_required_to_create_a_new_medical_release()
    {
        $data = array_except($this->getFormData(), ['name']);

        $response = $this->postJson('/api/medical-releases', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function ins_provider_is_required_when_ins_policy_no_is_present_to_create_a_new_medical_release()
    {
        $data = array_except($this->getFormData(), ['ins_provider']);

        $response = $this->postJson('/api/medical-releases', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['ins_provider']);
    }

    /** @test */
    public function ins_policy_no_is_required_when_ins_provider_is_present_to_create_a_new_medical_release()
    {
        $data = array_except($this->getFormData(), ['ins_policy_no']);

        $response = $this->postJson('/api/medical-releases', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['ins_policy_no']);
    }

    /** @test */
    public function takes_medication_is_required_to_create_a_new_medical_release()
    {
        $data = array_except($this->getFormData(), ['takes_medication']);

        $response = $this->postJson('/api/medical-releases', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['takes_medication']);
    }

    /** @test */
    public function takes_medication_must_be_a_valid_format_to_create_a_new_medical_release()
    {
        $data = $this->getFormData(['takes_medication' => 'invalid']);

        $response = $this->postJson('/api/medical-releases', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['takes_medication']);
    }

    /** @test */
    public function emergency_contact_is_required_to_create_a_new_medical_release()
    {
        $data = array_except($this->getFormData(), ['emergency_contact']);

        $response = $this->postJson('/api/medical-releases', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['emergency_contact']);
    }

    /** @test */
    public function emergency_contact_must_be_valid_to_create_a_new_medical_release()
    {
        $data = $this->getFormData(['emergency_contact' => 'invalid']);

        $response = $this->postJson('/api/medical-releases', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['emergency_contact']);
    }

    /** @test */
    public function emergency_contact_name_is_required_to_create_a_new_medical_release()
    {
        $data = array_except($this->getFormData(), ['emergency_contact.name']);

        $response = $this->postJson('/api/medical-releases', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['emergency_contact.name']);
    }

    /** @test */
    public function emergency_contact_email_is_required_to_create_a_new_medical_release()
    {
        $data = array_except($this->getFormData(), ['emergency_contact.email']);

        $response = $this->postJson('/api/medical-releases', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['emergency_contact.email']);
    }

    /** @test */
    public function emergency_contact_email_is_valid_to_create_a_new_medical_release()
    {
        $array = $this->getFormData();

        $data = array_merge(
            $array, 
            [
                'emergency_contact' => array_merge(
                    $array['emergency_contact'], 
                    ['email' => 'invalid']
                )
            ]
        );

        $response = $this->postJson('/api/medical-releases', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['emergency_contact.email']);
    }

    /** @test */
    public function emergency_contact_phone_is_required_to_create_a_new_medical_release()
    {
        $data = array_except($this->getFormData(), ['emergency_contact.phone']);

        $response = $this->postJson('/api/medical-releases', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['emergency_contact.phone']);
    }

    /** @test */
    public function emergency_contact_relationship_is_required_to_create_a_new_medical_release()
    {
        $data = array_except($this->getFormData(), ['emergency_contact.relationship']);

        $response = $this->postJson('/api/medical-releases', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['emergency_contact.relationship']);
    }

    /** @test */
    public function emergency_contact_relationship_is_valid_to_create_a_new_medical_release()
    {
        $array = $this->getFormData();

        $data = array_merge(
            $array, 
            [
                'emergency_contact' => array_merge(
                    $array['emergency_contact'], 
                    ['relationship' => 'invalid']
                )
            ]
        );

        $response = $this->postJson('/api/medical-releases', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['emergency_contact.relationship']);
    }

    /** @test */
    public function height_is_required_to_create_a_new_medical_release()
    {
        $data = array_except($this->getFormData(), ['height']);

        $response = $this->postJson('/api/medical-releases', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['height']);
    }

     /** @test */
    public function weight_is_required_to_create_a_new_medical_release()
    {
        $data = array_except($this->getFormData(), ['weight']);

        $response = $this->postJson('/api/medical-releases', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['weight']);
    }

     /** @test */
    public function pregnant_must_be_valid_to_create_a_new_medical_release()
    {
        $data = $this->getFormData(['pregnant' => 'invalid']);

        $response = $this->postJson('/api/medical-releases', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['pregnant']);
    }

    /**
     * Build an array of form data.
     * 
     * @param  array  $input [description]
     * @return array
     */
    private function getFormData($input = [])
    {
        $data = [
            'user_id'                        => factory(User::class)->create()->id,
            'name'                           => 'John Doe',
            'ins_provider'                   => 'Blue Shield of California',
            'ins_policy_no'                  => 'ABC1234567',
            'takes_medication'               => true,
            'conditions'                     => [
                                                    ['name' => 'Asthma']
                                                ],
            'allergies'                      => [
                                                    ['name' => 'Peanuts']
                                                ],
            'emergency_contact'              => [
                                                    'name' => 'Jane doe',
                                                    'email' => 'jane@gmail.com',
                                                    'phone' => '1234567890',
                                                    'relationship' => 'spouse'
                                                ],
            'pregnant'                       => false,
            'height'                         => 180,
            'weight'                         => 75
        ];

        return array_merge($data, $input);
    }
}
