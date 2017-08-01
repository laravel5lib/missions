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
        $conditions = factory(App\Models\v1\RequirementCondition::class, 2)->create([
            'requirement_id' => $requirement->id
        ]);

        $this->get('api/requirements/' . $requirement->id . '/conditions')
             ->assertResponseOk()
             ->seeJsonStructure([
                'data' => [
                    '*' => [
                        'requirement_id',
                        'type',
                        'operator',
                        'applies_to',
                        'created_at',
                        'updated_at'
                    ]
                ]
             ]);
    }

    /** @test */
    public function creates_a_new_requirement_condition()
    {
        $requirement = factory(App\Models\v1\Requirement::class)->create();

        $data = [
            'type' => 'role',
            'operator' => 'equal_to',
            'applies_to' => ['aBc', 'Def']
        ];

        $this->post('api/requirements/' . $requirement->id . '/conditions', $data)
             ->assertResponseOk()
             ->seeJson([
                'requirement_id' => $requirement->id,
                'type' => 'role',
                'operator' => 'equal_to',
                'applies_to' => ['ABC', 'DEF']
             ]);
    }

    /** @test */
    public function updates_a_requirement_condition()
    {
        $requirement = factory(App\Models\v1\Requirement::class)->create();
        $condition = factory(App\Models\v1\RequirementCondition::class)->create([
            'requirement_id' => $requirement->id,
            'type' => 'role'
        ]);

        $changes = [
            'operator' => 'not_equal_to',
            'applies_to' => ['Ghi', 'jKL']
        ];

        $this->put('api/requirements/' . $requirement->id . '/conditions/' . $condition->id, $changes)
             ->assertResponseOk()
             ->seeJson([
                'requirement_id' => $requirement->id,
                'type' => 'role',
                'operator' => 'not_equal_to',
                'applies_to' => ['GHI', 'JKL']
             ]);
    }

    /** @test */
    public function deletes_a_requirement_condition()
    {
        $requirement = factory(App\Models\v1\Requirement::class)->create();
        $condition = factory(App\Models\v1\RequirementCondition::class)->create([
            'requirement_id' => $requirement->id
        ]);

        $this->delete('api/requirements/' . $requirement->id . '/conditions/' . $condition->id)
             ->dontSeeInDatabase('requirement_conditions', $condition->toArray())
             ->assertResponseStatus(204);
    }
}
