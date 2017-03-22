<?php

use App\Models\v1\Group;

class GroupTest extends TestCase
{
    /** @test */
    public function defaults_to_approved_on_creation()
    {
        $group = factory(Group::class)->make();

        $this->assertEquals('approved', $group->status);
    }

    /** @test */
    public function overrides_default_status()
    {
        $group = factory(Group::class)->make(['status' => 'pending']);

        $this->assertEquals('pending', $group->status);
    }
}
