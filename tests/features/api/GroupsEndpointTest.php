<?php

use App\Models\v1\Group;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GroupsEndpointTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /**
     * @test
     */
    public function submits_group_request()
    {
        $groupRequest = factory(Group::class)->make()->toArray();

        $groupRequest = array_merge($groupRequest, [
            'contact' => 'John Doe',
            'position' => 'pastor',
            'spoke_to_rep' => 'yes',
            'campaign' => '1Nation1Day 2017'
        ]);

        $this->post('/api/groups/submit?include=notes', $groupRequest)
            ->assertResponseOk()
            ->seeJson([
                    'status' => 'pending',
                    'public' => false
            ]);
    }
}