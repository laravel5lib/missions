<?php

namespace Tests\Feature\Api\Credentials;

use Tests\TestCase;
use App\Models\v1\User;
use App\Models\v1\Reservation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateMedicalCredentialTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function create_new_medical_credential_and_save_in_storage()
    {
        $data = $this->formData();

        $response = $this->postJson('/api/medical-credentials', $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas(
            'credentials', 
            array_merge($data, ['type' => 'medical', 'content' => castToJson($data['content'])])
        );
    }

    /** @test */
    public function attach_medical_credential_to_reservation_when_created()
    {
        $reservation = factory(Reservation::class)->create();

        $data = $this->formData(['reservation_id' => $reservation->id]);

        $response = $this->postJson('/api/medical-credentials', $data);

        $response->assertStatus(201);

        $this->assertDatabaseHas(
            'reservation_documents', 
            ['reservation_id' => $reservation->id, 'documentable_type' => 'medical_credentials']
        );
    }

    private function formData($data = [])
    {
        $array = [
            'user_id' => factory(User::class)->create()->id,
            'applicant_name' => 'John Doe',
            'content' => ['key' => 'value'],
            'expired_at' => null
        ];

        return array_merge($array, $data);
    }
}
