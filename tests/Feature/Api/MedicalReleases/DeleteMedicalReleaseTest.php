<?php

namespace Tests\Feature\Api\MedicalReleases;

use Tests\TestCase;
use App\Models\v1\Reservation;
use App\Models\v1\MedicalRelease;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteMedicalReleaseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function delete_medical_release()
    {
        $release = factory(MedicalRelease::class)->create();
        $release->syncConditions([['name' => 'Asthma']]);
        $release->syncAllergies([['name' => 'Peanuts']]);

        $response = $this->deleteJson("/api/medical-releases/{$release->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('medical_releases', ['id' => $release->id]);
        $this->assertDatabaseMissing('medical_conditions', ['medical_release_id' => $release->id]);
        $this->assertDatabaseMissing('medical_allergies', ['medical_release_id' => $release->id]);
    }

     /** @test */
    public function detach_all_reservations_when_medical_release_is_deleted()
    {
        $release = factory(MedicalRelease::class)->create();

        $reservation = factory(Reservation::class)->create();

        $release->attachToReservation($reservation->id);

        $response = $this->deleteJson("/api/medical-releases/{$release->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing(
            'reservation_documents', 
            [
                'reservation_id' => $reservation->id,
                'documentable_id' => $release->id,
                'documentable_type' => 'medical_releases'
            ]
        );
    }
}
