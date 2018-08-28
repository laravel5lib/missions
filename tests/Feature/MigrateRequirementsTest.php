<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\v1\Trip;
use App\Models\v1\Campaign;
use App\Models\v1\Requirement;
use App\Models\v1\Reservation;
use Illuminate\Support\Facades\DB;
use App\Models\v1\RequirementCondition;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MigrateRequirementsTest extends TestCase
{
    use RefreshDatabase, withFaker;

    /** @test */
    public function migrates_requirements()
    {
        $campaign = factory(Campaign::class)->create();

        factory(Requirement::class, 10)->create([
            'requester_type' => 'campaigns', 
            'requester_id' => $campaign->id
        ]);

        Artisan::call('migrate:requirements');

        $this->assertEquals(DB::table('requireables')->count(), 10);
        $this->assertDatabaseHas(
            'requireables', 
            ['requireable_type' => 'campaigns', 'requireable_id' => $campaign->id]
        );
        $this->assertDatabaseMissing(
            'requireables', 
            ['created_at' => null, 'updated_at' => null]
        );
    }

    /** @test */
    public function migrates_requirement_conditions()
    {
        $requirement = factory(Requirement::class)->create([
            'name' => 'Media Credential', 
            'requester_type' => 'campaigns', 
            'requester_id' => factory(Campaign::class)->create()->id
        ]);

        factory(RequirementCondition::class)->create([
            'requirement_id' => $requirement->id,
            'type' => 'role',
            'operator' => 'equal_to',
            'applies_to' => ['MEDI']
        ]);

        Artisan::call('migrate:requirements');

        $requirement = $requirement->fresh();

        $this->assertEquals(['roles' => ['MEDI']], $requirement->rules);
    }

    /** @test */
    public function migrates_reservation_requirements_and_documents()
    {
        $trip = factory(Trip::class)->create();

        $reservationId = factory(Reservation::class)->create(['trip_id' => $trip->id])->id;
        $requirementId = factory(Requirement::class)->create(['requester_id' => $trip->id, 'requester_type' => 'trips'])->id;
        $documentId = $this->faker->uuid;

        DB::table('reservation_requirements')->insert([
            'id' => $this->faker->uuid,
            'requirement_id' => $requirementId, 
            'reservation_id' => $reservationId, 
            'document_id' => $documentId,
            'document_type' => 'passports',
            'status' => 'complete',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Artisan::call('migrate:requirements');

        $this->assertDatabaseHas(
            'requireables', 
            [
                'requirement_id' => $requirementId, 
                'requireable_id' => $reservationId, 
                'requireable_type' => 'reservations',
                'status' => 'complete'
            ]
        );

        $this->assertDatabaseMissing(
            'requireables', 
            ['created_at' => null, 'updated_at' => null]
        );

        $this->assertDatabaseHas(
            'reservation_documents', 
            [
                'reservation_id' => $reservationId, 
                'documentable_type' => 'passports',
                'documentable_id' => $documentId
            ]
        );
    }
}
