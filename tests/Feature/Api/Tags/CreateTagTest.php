<?php

namespace Tests\Feature\Api\Tags;

use Tests\TestCase;
use Spatie\Tags\Tag;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateTagTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function create_new_tag()
    {
        $this->postJson('/api/tags/trip', ['name' => 'includes flight'])->assertStatus(201);

        $this->assertDatabaseHas('tags', [
            'name' => castToJson(['en' => 'includes flight']),
            'type' => 'trip'
        ]);
    }

    /** @test */
    public function tag_name_must_be_unique_to_create_new_tag()
    {
        $tag = factory(Tag::class)->create(['name' => 'includes flight', 'type' => 'trip']);

        $this->postJson('/api/tags/trip', ['name' => 'includes flight'])
             ->assertStatus(422)
             ->assertJsonValidationErrors(['name']);
    }
}
