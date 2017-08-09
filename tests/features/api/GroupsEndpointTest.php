<?php

class GroupsEndpointTest extends BrowserKitTestCase
{
    /**
     * @test
     */
    public function submits_group_request()
    {

        $groupRequest = [
            'contact'      => 'John Doe',
            'position'     => 'pastor',
            'spoke_to_rep' => 'yes',
            'campaign'     => '1Nation1Day 2017',
            'name'         => 'test group',
            'type'         => 'church',
            'timezone'     => 'America/Detroit',
            'country_code' => 'us',
            'email'        => 'test@email.com',
            'phone_one'    => '1234567890'
        ];

        $this->post('/api/groups/submit?include=notes', $groupRequest)
            ->assertResponseOk()
            ->seeJson([
                    'status' => 'pending',
                    'public' => false
            ]);
    }
}
