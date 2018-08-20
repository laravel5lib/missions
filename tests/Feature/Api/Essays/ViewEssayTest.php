<?php

namespace Tests\Feature\Api\Essays;

use Tests\TestCase;
use App\Models\v1\Trip;
use App\Models\v1\User;
use App\Models\v1\Essay;
use App\Models\v1\Group;
use App\Models\v1\Reservation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewEssayTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function get_list_of_testimonies()
    {
        factory(Essay::class, 2)->create(['subject' => 'Testimony']);
        factory(Essay::class, 2)->create(['subject' => 'Influencer']);

        $response = $this->getJson('/api/essays');

        $response->assertOk()
                 ->assertJsonStructure([
                    'data' => [
                        [
                            'id', 'user_id', 'author_name', 'subject', 'content', 'created_at', 'updated_at'
                        ]
                    ]
                 ])
                 ->assertJson([
                    'data' => [
                        ['subject' => 'Testimony']
                    ],
                    'meta' => [
                        'total' => 2
                    ]
                 ]);
    }

    /** @test */
    public function get_testimony_by_id()
    {
        $essay = factory(Essay::class)->create(['subject' => 'Testimony']);

        $response = $this->getJson("/api/essays/{$essay->id}");

        $response->assertOk()
                 ->assertJsonStructure([
                    'data' => ['user']
                 ])
                 ->assertJson([
                    'data' => [
                        'id' => $essay->id
                    ]
                ]);
    }

    /** @test */
    public function get_influencer_application_by_id()
    {
        $essay = factory(Essay::class)->create(['subject' => 'Influencer']);

        $response = $this->getJson("/api/essays/{$essay->id}");

        $response->assertOk()
                 ->assertJsonStructure([
                    'data' => ['user']
                 ])
                 ->assertJson([
                    'data' => [
                        'id' => $essay->id
                    ]
                ]);
    }

    /** @test */
    public function get_list_of_influencer_applications()
    {
        factory(Essay::class, 2)->create(['subject' => 'Testimony']);
        factory(Essay::class, 2)->create(['subject' => 'Influencer']);

        $response = $this->getJson('/api/influencer-applications');

        $response->assertOk()
                 ->assertJsonStructure([
                    'data' => [
                        [
                            'id', 'user_id', 'author_name', 'subject', 'content', 'created_at', 'updated_at'
                        ]
                    ]
                 ])
                 ->assertJson([
                    'data' => [
                        ['subject' => 'Influencer']
                    ],
                    'meta' => [
                        'total' => 2
                    ]
                 ]);
    }

    /** @test */
    public function filter_essays_by_author_name()
    {
        factory(Essay::class)->create(['author_name' => 'John Doe']);
        factory(Essay::class)->create(['author_name' => 'Jane Smith']);

        $response = $this->getJson('/api/essays?filter[author_name]=John');

        $response->assertOk()
                 ->assertJson([
                    'data' => [
                        ['author_name' => 'John Doe']
                    ]
                 ])
                 ->assertJsonMissing([
                    'data' => [
                        ['author_name' => 'Jane Smith']
                    ]
                 ]);
    }

    /** @test */
    public function filter_essays_by_user_id()
    {
        $user = factory(User::class)->create();
        factory(Essay::class)->create(['user_id' => $user->id]);
        $essay = factory(Essay::class)->create();

        $response = $this->getJson("/api/essays?filter[user_id]={$user->id}");

        $response->assertOk()
                 ->assertJson([
                    'data' => [
                        ['user_id' => $user->id]
                    ]
                 ])
                 ->assertJsonMissing([
                    'data' => [
                        ['user_id' => $essay->user_id]
                    ]
                 ]);
    }

    /** @test */
    public function get_essays_only_for_users_with_reservations_managed_by_team_coordinator()
    {
        $coordinator = factory(User::class)->create();
        $group = factory(Group::class)->create();
        $group->managers()->attach($coordinator->id);
        $trip = factory(Trip::class)->create(['group_id' => $group->id]);
        $reservation = factory(Reservation::class)->create(['trip_id' => $trip->id]);
        $release = factory(Essay::class)->create(['user_id' => $reservation->user_id, 'author_name' => 'John Doe']);
        factory(Essay::class)->create(['author_name' => 'Jane Doe']);

        $response = $this->getJson("/api/essays?filter[managed_by]={$coordinator->id}");

        $response->assertOk()
                 ->assertJson([
                    'data' => [
                        [
                            'author_name' => 'John Doe',
                            'user_id' => $reservation->user_id
                        ]
                    ]
                 ])
                 ->assertJsonMissing([
                    'data' => [
                        ['author_name' => 'Jane Doe']
                    ]
                 ]);
    }
}
