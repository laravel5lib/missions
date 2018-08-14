<?php

namespace Tests\Feature\Api\Passports;

use Tests\TestCase;
use App\Models\v1\Trip;
use App\Models\v1\User;
use App\Models\v1\Group;
use App\Models\v1\Passport;
use App\Models\v1\Reservation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewPassportTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function view_all_passports()
    {
        factory(Passport::class, 2)->create();

        $response = $this->getJson('/api/passports');

        $response->assertOk()
                 ->assertJsonStructure([
                    'data' => [
                        [
                            'id',
                            'given_names',
                            'surname',
                            'number',
                            'birth_country',
                            'birth_country_name',
                            'citizenship',
                            'citizenship_name',
                            'upload_id',
                            'expires_at',
                            'created_at',
                            'updated_at',
                            'expired'
                        ]
                    ]
                 ])
                 ->assertJson([
                    'meta' => ['total' => 2]
                 ]);
    }

    /** @test */
    public function view_passport_by_id()
    {
        $passport = factory(Passport::class)->create([
            'given_names' => 'John Michael',
            'surname' => 'Doe',
            'number' => '1234567890'
        ]);

        $response = $this->getJson("/api/passports/{$passport->id}");

        $response->assertOk()
                 ->assertJson([
                    'data' => [
                        'id' => $passport->id,
                        'given_names' => 'John Michael',
                        'surname' => 'Doe',
                        'number' => '1234567890'
                    ]
                 ]);
    }

    /** @test */
    public function include_user_with_passport()
    {
        $user = factory(User::class)->create(['first_name' => 'John', 'last_name' => 'Doe']);
        $passport = factory(Passport::class)->create(['user_id' => $user->id]);

        $response = $this->getJson("/api/passports?include=user");

        $response->assertOk()
                 ->assertJson([
                    'data' => [
                        [
                            'id' => $passport->id,
                            'user' => [
                                'id' => $user->id,
                                'first_name' => 'John',
                                'last_name' => 'Doe'
                            ]
                        ]
                    ]
                 ]);
    }

    /** @test */
    public function filter_passports_by_given_names()
    {
        factory(Passport::class)->create(['given_names' => 'John Michael', 'surname' => 'Doe']);
        factory(Passport::class)->create(['given_names' => 'Jane Michelle', 'surname' => 'Doe']);

        $response = $this->getJson("/api/passports?filter[given_names]=john");

        $response->assertOk()
                 ->assertJson([
                    'data' => [
                        [
                            'given_names' => 'John Michael',
                            'surname' => 'Doe'
                        ]
                    ]
                 ])
                 ->assertJsonMissing([
                    'data' => [
                        [
                            'given_names' => 'Jane Michelle',
                            'surname' => 'Doe'
                        ]
                    ]
                 ]);
    }

    /** @test */
    public function filter_passports_by_surname()
    {
        factory(Passport::class)->create(['given_names' => 'John Michael', 'surname' => 'Doe']);
        factory(Passport::class)->create(['given_names' => 'Jane Michelle', 'surname' => 'Smith']);

        $response = $this->getJson("/api/passports?filter[surname]=doe");

        $response->assertOk()
                 ->assertJson([
                    'data' => [
                        [
                            'given_names' => 'John Michael',
                            'surname' => 'Doe'
                        ]
                    ]
                 ])
                 ->assertJsonMissing([
                    'data' => [
                        [
                            'given_names' => 'Jane Michelle',
                            'surname' => 'Smith'
                        ]
                    ]
                 ]);
    }

    /** @test */
    public function filter_passports_by_number()
    {
        factory(Passport::class)->create(['number' => 'ABC123']);
        factory(Passport::class)->create(['number' => 'DEF456']);

        $response = $this->getJson("/api/passports?filter[number]=123");

        $response->assertOk()
                 ->assertJson([
                    'data' => [
                        [
                            'number' => 'ABC123',
                        ]
                    ]
                 ])
                 ->assertJsonMissing([
                    'data' => [
                        [
                            'number' => 'DEF456'
                        ]
                    ]
                 ]);
    }

    /** @test */
    public function filter_passports_by_user_id()
    {
        $user = factory(User::class)->create();
        factory(Passport::class)->create(['user_id' => $user->id]);
        factory(Passport::class)->create();

        $response = $this->getJson("/api/passports?filter[user_id]={$user->id}");

        $response->assertOk()
                 ->assertJson([
                    'data' => [
                        [
                            'user_id' => $user->id,
                        ]
                    ],
                    'meta' => ['total' => 1]
                 ]);
    }

    /** @test */
    public function get_passports_only_for_users_with_reservations_managed_by_team_coordinator()
    {
        $coordinator = factory(User::class)->create();
        $group = factory(Group::class)->create();
        $group->managers()->attach($coordinator->id);
        $trip = factory(Trip::class)->create(['group_id' => $group->id]);
        $reservation = factory(Reservation::class)->create(['trip_id' => $trip->id]);
        $passport = factory(Passport::class)->create(['user_id' => $reservation->user_id, 'given_names' => 'John Michael', 'surname' => 'Doe']);
        factory(Passport::class)->create(['given_names' => 'Jane Michelle', 'surname' => 'Doe']);

        $response = $this->getJson("/api/passports?filter[managed_by]={$coordinator->id}");

        $response->assertOk()
                 ->assertJson([
                    'data' => [
                        [
                            'given_names' => 'John Michael',
                            'surname' => 'Doe',
                            'user_id' => $reservation->user_id
                        ]
                    ]
                 ])
                 ->assertJsonMissing([
                    'data' => [
                        ['given_names' => 'Jane Michelle', 'surname' => 'Doe']
                    ]
                 ]);
    }
}
