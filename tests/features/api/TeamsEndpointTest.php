<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TeamsEndpointTest extends TestCase
{
    /** @test */
    public function fetches_all_teams()
    {
        $teams = factory(App\Models\v1\Team::class, 2)->create();

        $this->get('api/teams')->assertResponseOk();
    }
}
