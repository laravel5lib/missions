<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GroupTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function defaults_to_approved_on_creation()
    {
        $group = factory(App\Models\v1\Group::class)->create();

        $this->seeInDatabase('groups', ['status' => 'approved']);
        $this->assertEquals('approved', $group->status);
    }

    /** @test */
    public function overrides_default_status()
    {
        $group = factory(App\Models\v1\Group::class)->create(['status' => 'pending']);

        $this->seeInDatabase('groups', ['status' => 'pending']);
        $this->assertEquals('pending', $group->status);
    }
}
