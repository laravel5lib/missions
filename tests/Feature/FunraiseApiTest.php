<?php

namespace Tests\Feature;

use Tests\TestCase;
use GuzzleHttp\Client;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FunraiseApiTest extends TestCase
{
    /** @test */
    public function connects_and_authenticates_with_funraise_api()
    {
        $client = new Client(['cookies' => true]);
        
        $response = $client->request('POST', 'https://platform.funraise.io/api/v1/login', [
            'form_params' => [ 'username' => 'neil@missions.me', 'password' => 'lakn2009t']
        ]);

        dd($response);
    }
}
