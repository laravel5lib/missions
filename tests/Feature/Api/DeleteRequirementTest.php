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
    public function soft_deletes_requirement()
    {
        $requirement = factory(Requirement::class)->create();

        $this->deleteJson("/api/requirements/{$requirement->id}")
             ->assertStatus(204);

        $this->assertNotNull($requirement->fresh()->deleted_at);
    }
}
