<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RequirementConditionsEndpointTest extends TestCase
{
    /** @test */
    public function fetches_a_requirements_conditions()
    {
        $requirement = factory(App\Models\v1\Requirement::class)->create();
        $conditions = factory(App\Models\v1\RequirementCondition::class, 'role', 2)->create([
            'requirement_id' => $requirement->id
        ]);

        $this->get('api/requirements/' . $requirement->id . '/conditions')
             ->assertResponseOk()
             ->seeJsonStructure([
                'data' => [
                    '*' => [
                        'requirement_id',
                        'type',
                        'condition',
                        'applies_to',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ]);
    }

    public function creates_a_new_requirement_condition()
    {
        //
    }

    public function updates_a_requirement_condition()
    {
        //
    }

    public function  deletes_a_requirement_condition()
    {
        //
    }
}
