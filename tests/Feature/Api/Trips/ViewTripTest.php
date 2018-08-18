<?php

namespace Tests\Feature\Api\Trips;

use Tests\TestCase;
use Spatie\Tags\Tag;
use App\Models\v1\Trip;
use App\Models\v1\CampaignGroup;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewTripTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function get_all_trips_for_a_group()
    {
        $otherTrips = factory(Trip::class, 4)->create();

        $group = factory(CampaignGroup::class)->create();
        $trip = factory(Trip::class)->create([
            'campaign_id' => $group->campaign_id, 'group_id' => $group->organization->id
        ]);

        $this->json('GET', "/api/trips?filter[group_id]={$group->organization->id}&filter[campaign_id]={$group->campaign_id}")
          ->assertStatus(200)
          ->assertJson([
            'data' => [
                [
                    'id' => $trip->id,
                    'group_id' => $group->organization->id,
                    'campaign_id' => $group->campaign_id
                ]
            ],
            'meta' => [
                'total' => 1
            ]
          ]);
    }

    /** @test */
    public function include_tags_with_trips_response()
    {
        $trip = factory(Trip::class)->create();
        $trip->attachTag('test');

        $response = $this->getJson('/api/trips?include=tags');

        $response->assertOk()
                 ->assertJsonStructure([
                    'data' => [
                        [
                            'tags' => [
                                [
                                    'name',
                                    'slug',
                                    'type',
                                    'order',
                                    'created_at',
                                    'updated_at'
                                ]
                            ]
                        ]
                    ]
                 ]);
    }

    /** @test */
    public function get_trip_by_id()
    {
        $trip = factory(Trip::class)->create();
        $tag = Tag::findOrCreate('flight included', 'trip');
        $trip->attachTag($tag);

        $response = $this->getJson("/api/trips/{$trip->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'tags',
                    'prices',
                    'group',
                    'campaign',
                    'requirements'
                ]
            ])
            ->assertJson([
                'data' => ['id' => $trip->id]
            ]);
    }
}
