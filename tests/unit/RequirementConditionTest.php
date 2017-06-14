<?php

class RequirementConditionTest extends TestCase
{

    /** @test */
    function sets_applies_to_attribute()
    {
        $requirement = factory(App\Models\v1\Requirement::class)->create();
        $condition = factory(App\Models\v1\RequirementCondition::class)->make([
            'requirement_id' => $requirement->id
        ]);

        $condition->applies_to = ["abc", "def", "jkl"];
        $condition->save();

        $this->assertEquals(["ABC", "DEF", "JKL"], $condition->applies_to);
    }
}