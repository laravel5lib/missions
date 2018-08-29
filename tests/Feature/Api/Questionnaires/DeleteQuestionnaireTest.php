<?php

namespace Tests\Feature\Api\Questionnaires;

use Tests\TestCase;
use App\Models\v1\MinorRelease;
use App\Models\v1\AirportPreference;
use App\Models\v1\ArrivalDesignation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteQuestionnaireTest extends TestCase
{
    /** @test */
    public function delete_airport_preference()
    {
        $questionnaire = factory(AirportPreference::class)->create();

        $response = $this->deleteJson("/api/airport-preferences/{$questionnaire->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('questionnaires', ['id' => $questionnaire->id]);
    }

    /** @test */
    public function delete_arrival_designation()
    {
        $questionnaire = factory(ArrivalDesignation::class)->create();

        $this->deleteJson("/api/arrival-designations/{$questionnaire->id}")
             ->assertStatus(204);

        $this->assertDatabaseMissing('questionnaires', ['id' => $questionnaire->id]);
    }

    /** @test */
    public function delete_minor_release()
    {
        $questionnaire = factory(MinorRelease::class)->create();

        $this->deleteJson("/api/minor-releases/{$questionnaire->id}")
             ->assertStatus(204);

        $this->assertDatabaseMissing('questionnaires', ['id' => $questionnaire->id]);
    }
}
