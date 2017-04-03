<?php

class ReservationTest extends TestCase
{

    /** @test */
    function sets_applies_to_attribute()
    {
        $condition = factory(App\Models\v1\RequirementCondition::class, 'role')->make();

        dd($condition);
    }
}