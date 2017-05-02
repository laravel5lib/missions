<?php

class RequirementConditionTest extends TestCase
{

    /** @test */
    function sets_applies_to_attribute()
    {
        $condition = factory(App\Models\v1\RequirementCondition::class, 'role')->make();

        $condition->applies_to = ["abc", "def", "jkl"];
        $condition->save();

        $this->assertEquals(["ABC", "DEF", "JKL"], $condition->applies_to);
    }
}