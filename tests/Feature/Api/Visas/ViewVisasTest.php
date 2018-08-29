<?php

namespace Tests\Feature\Api\Visas;

use Tests\TestCase;
use App\Models\v1\Trip;
use App\Models\v1\User;
use App\Models\v1\Visa;
use App\Models\v1\Group;
use App\Models\v1\Reservation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewVisasTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function view_all_visas()
    {
        factory(Visa::class, 2)->create();

        $response = $this->getJson('/api/visas');

        $response->assertOk()
                 ->assertJsonStructure([
                    'data' => [
                        [
                            'id',
                            'user_id',
                            'given_names',
                            'surname',
                            'number',
                            'country_code',
                            'country_name',
                            'upload_id',
                            'issued_at',
                            'expires_at',
                            'created_at',
                            'updated_at',
                            'expired'
                        ]
                    ]
                 ])
                 ->assertJson(['meta' => ['total' => 2]]);
    }

    /** @test */
    public function view_visa_by_id()
    {
        $visa = factory(Visa::class)->create();

        $response = $this->getJson("/api/visas/{$visa->id}");

        $response->assertOk()
                 ->assertJsonStructure(['data' => ['user']])
                 ->assertJson(['data' => ['id' => $visa->id]]);
    }

    /** @test */
    public function include_user_with_visa()
    {
        $user = factory(User::class)->create(['first_name' => 'John', 'last_name' => 'Doe']);
        $visa = factory(Visa::class)->create(['user_id' => $user->id]);

        $response = $this->getJson("/api/visas?include=user");

        $response->assertOk()
                 ->assertJson([
                    'data' => [
                        [
                            'id' => $visa->id,
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
    public function filter_visas_by_given_names()
    {
        factory(Visa::class)->create(['given_names' => 'John Michael', 'surname' => 'Doe']);
        factory(Visa::class)->create(['given_names' => 'Jane Michelle', 'surname' => 'Doe']);

        $response = $this->getJson("/api/visas?filter[given_names]=john");

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
    public function filter_visas_by_surname()
    {
        factory(Visa::class)->create(['given_names' => 'John Michael', 'surname' => 'Doe']);
        factory(Visa::class)->create(['given_names' => 'Jane Michelle', 'surname' => 'Smith']);

        $response = $this->getJson("/api/visas?filter[surname]=doe");

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
    public function filter_visas_by_number()
    {
        factory(Visa::class)->create(['number' => 'ABC123']);
        factory(Visa::class)->create(['number' => 'DEF456']);

        $response = $this->getJson("/api/visas?filter[number]=ABC123");

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
    public function filter_visas_by_country_code()
    {
        factory(Visa::class)->create(['country_code' => 'in']);
        factory(Visa::class)->create(['country_code' => 'dr']);

        $response = $this->getJson("/api/visas?filter[country_code]=in");

        $response->assertOk()
                 ->assertJson([
                    'data' => [
                        [
                            'country_code' => 'in',
                        ]
                    ]
                 ])
                 ->assertJsonMissing([
                    'data' => [
                        [
                            'country_code' => 'dr'
                        ]
                    ]
                 ]);
    }

    /** @test */
    public function filter_visas_by_user_id()
    {
        $user = factory(User::class)->create();
        factory(Visa::class)->create(['user_id' => $user->id]);
        factory(Visa::class)->create();

        $response = $this->getJson("/api/visas?filter[user_id]={$user->id}");

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
    public function get_visas_only_for_users_with_reservations_managed_by_team_coordinator()
    {
        $coordinator = factory(User::class)->create();
        $group = factory(Group::class)->create();
        $group->managers()->attach($coordinator->id);
        $trip = factory(Trip::class)->create(['group_id' => $group->id]);
        $reservation = factory(Reservation::class)->create(['trip_id' => $trip->id]);
        $visa = factory(Visa::class)->create(['user_id' => $reservation->user_id, 'given_names' => 'John Michael', 'surname' => 'Doe']);
        factory(Visa::class)->create(['given_names' => 'Jane Michelle', 'surname' => 'Doe']);

        $response = $this->getJson("/api/visas?filter[managed_by]={$coordinator->id}");

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
