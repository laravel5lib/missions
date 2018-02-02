<?php

use App\Models\v1\User;

trait AuthenticatedUserSetup
{
    public function setUp()
    {
        parent::setUp();

        $this->actingAs(User::first() ?: factory(User::class)->create(), 'api');
    }
}
