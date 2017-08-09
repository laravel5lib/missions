<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HubsEndpointTest extends BrowserKitTestCase
{
    /** @test */
    public function fetches_all_hubs()
    {
        $this->get('api/hubs')->assertResponseOk();
    }
}
