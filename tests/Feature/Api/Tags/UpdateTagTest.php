<?php

namespace Tests\Feature\Api\Tags;

use Tests\TestCase;
use Spatie\Tags\Tag;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateTagTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function rename_tag()
    {
        $tag = factory(Tag::class)->create(['name' => 'cool tag', 'type' => 'generic']);

        $response = $this->patchJson("/api/tags/{$tag->type}/{$tag->id}", ['name' => 'awesome tag']);

        $response->assertOk()
                 ->assertJson([
                    'data' => [
                        'name' => 'awesome tag',
                        'slug' => 'awesome-tag',
                        'type' => 'generic'
                    ]
                 ]);
    }

    /** @test */
    public function tag_name_for_the_given_type_must_be_unique_to_rename_tag()
    {
        factory(Tag::class)->create(['name' => 'awesome tag', 'type' => 'generic']);
        $tag = factory(Tag::class)->create(['name' => 'cool tag', 'type' => 'generic']);

        $response = $this->patchJson("/api/tags/{$tag->type}/{$tag->id}", ['name' => 'awesome tag']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function tag_name_can_be_the_same_as_one_of_a_different_type_to_rename_tag()
    {
        factory(Tag::class)->create(['name' => 'awesome tag', 'type' => 'other']);
        $tag = factory(Tag::class)->create(['name' => 'cool tag', 'type' => 'generic']);

        $response = $this->patchJson("/api/tags/{$tag->type}/{$tag->id}", ['name' => 'awesome tag']);

        $response->assertOk();
    }
}
