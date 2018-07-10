<?php

namespace Tests\Feature\Api;

use App\Models\v1\Requirement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteRequirementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function deletes_requirement_and_removes_from_storage()
    {
        $requirement = factory(Requirement::class)->create();

        $this->deleteJson("/api/requirements/{$requirement->id}")
             ->assertStatus(204);

        $this->assertDatabaseMissing('requirements', ['id' => $requirement->id]);
    }
}
