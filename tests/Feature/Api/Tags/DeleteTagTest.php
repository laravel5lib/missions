<?php

namespace Tests\Feature\Api\Tags;

use Tests\TestCase;
use Spatie\Tags\Tag;
use App\Models\v1\Trip;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteTagTest extends TestCase
{
    /** @test */
    public function delete_tag_by_id()
    {
        $tag = factory(Tag::class)->create();

        $this->deleteJson("/api/tags/{$tag->id}")->assertStatus(204);

        $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
    }

    /** @test */
    public function delete_tag_and_remove_from_any_tagged_resources()
    {
        $tag = factory(Tag::class)->create();
        $trip = factory(Trip::class)->create();
        $trip->attachTag('test');

        $this->deleteJson("/api/tags/{$tag->id}")->assertStatus(204);

        $this->assertDatabaseMissing('taggables', ['tag_id' => $tag->id]);
    }
}
