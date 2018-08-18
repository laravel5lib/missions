<?php

namespace Tests\Feature\Api\Tags;

use Tests\TestCase;
use Spatie\Tags\Tag;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewTagTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function get_a_list_of_all_tags()
    {
        factory(Tag::class, 2)->create();

        $response = $this->getJson('/api/tags');

        $response->assertOk()
                 ->assertJsonStructure([
                    'data' => [
                        [
                            'name',
                            'slug',
                            'type',
                            'order',
                            'created_at',
                            'updated_at'
                        ]
                    ]
                 ]);
    }

    /** @test */
    public function filter_list_of_tags_by_type()
    {
        factory(Tag::class)->create(['name' => 'flight included', 'type' => 'trip']);
        factory(Tag::class)->create(['name' => 'vip', 'type' => 'reservation']);

        $response = $this->getJson('/api/tags?filter[type]=trip');

        $response->assertOk()
                 ->assertJson([
                    'data' => [
                        ['name' => 'flight included', 'type' => 'trip']
                    ]
                 ])
                 ->assertJsonMissing([
                    'data' => [
                        ['name' => 'vip', 'type' => 'reservation']
                    ]
                 ]);
    }

    /** @test */
    public function get_tags_of_a_specific_type()
    {
        factory(Tag::class)->create(['name' => 'flight included', 'type' => 'trip']);
        factory(Tag::class)->create(['name' => 'vip', 'type' => 'reservation']);

        $response = $this->getJson('/api/tags/trip');

        $response->assertOk()
                 ->assertJson([
                    'data' => [
                        ['name' => 'flight included', 'type' => 'trip']
                    ]
                 ])
                 ->assertJsonMissing([
                    'data' => [
                        ['name' => 'vip', 'type' => 'reservation']
                    ]
                 ]);
    }

    /** @test */
    public function filter_tags_by_name()
    {
        factory(Tag::class)->create(['name' => 'flight included', 'type' => 'trip']);
        factory(Tag::class)->create(['name' => 'vip', 'type' => 'reservation']);

        $response = $this->getJson('/api/tags?filter[name]=flight');

        $response->assertOk()
                 ->assertJson([
                    'data' => [
                        ['name' => 'flight included', 'type' => 'trip']
                    ]
                 ])
                 ->assertJsonMissing([
                    'data' => [
                        ['name' => 'vip', 'type' => 'reservation']
                    ]
                 ]);
    }
}
