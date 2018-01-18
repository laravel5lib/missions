<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\v1\Trip;
use App\Models\v1\User;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TripRegistrationTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }
    
    /** @test */
    public function user_registers_for_a_trip()
    {
        Passport::actingAs($this->user);
        
        $trip = factory(Trip::class)->create();
        $trip->costs()->save(factory(Cost::class)->make());
        $trip->deadlines()->save(factory(Deadline::class)->make());

        $response = $this->json('POST', "/api/trips/{$trip->id}/register", $this->formData());
        
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'given_names' => 'John Smith',
                'surname' => 'Doe'
            ]
        ]);

        $reservation = $trip->reservations->first();

        $this->assertNotNull($reservation->fund->id);
        $this->assertNotEmpty($reservation->todos);
    }

    private function formData()
    {
        return [
            'given_names'        => 'John Smith',
            'surname'            => 'Doe',
            'gender'             => 'male',
            'status'             => 'single',
            'shirt_size'         => 'M',
            'birthday'           => '1987-07-28',
            'user_id'            => $this->user->id,
            'address'            => '123 Some Place Rd.',
            'zip'                => '12345',
            'city'               => 'Some City',
            'country_code'       => 'us',
            'email'              => 'jsdoe@example.com',
            'phone_one'          => '1234567890',
            'phone_two'          => '1234567890',
            'donor'              => [
                'first_name'   => 'John',
                'last_name'    => 'Doe',
                'email'        => 'jsdoe@example.com',
                'zip'          => '12345',
                'country_code' => 'us',
                'account_id'   => $this->user->id,
                'account_type' => 'users',
            ],
            'amount'             => 100,
            'token'              => 'tok_visa',
            'description'        => 'Deposit for reservation.',
            'currency'           => 'USD',
            'payment'            => [
                'type' => 'card'
            ]
        ];
    }
}
