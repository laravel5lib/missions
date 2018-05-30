<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\v1\Lead;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LeadTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function create_new_organization_lead()
    {
        $data = [ 
            'category_id' => 1,
            'content' => [
                'name' => 'Victory Church',
                'type' => 'church',
                'timezone' => 'American/Detroit',
                'address_one' => '123 Main Street',
                'address_two' => '',
                'city' => 'Detroit',
                'state' => 'MI',
                'zip' => '12345',
                'country' => 'us',
                'phone_one' => '1234567890',
                'phone_two' => '1234567890',
                'email' => 'john@email.com',
                'contact' => 'John Doe',
                'position' => 'Associate Pastor',
                'spoke_with_rep' => false,
                'campaign_of_interest' => '1Nation1Day 2019'
            ]
        ];

        $this->json('POST', '/api/leads', $data)->assertStatus(201);

        $lead = Lead::first();
        $this->assertEquals($lead->category_id, 1);
        $this->assertEquals($lead->content, $data['content']);
    }
}
