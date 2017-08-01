<?php

use App\Models\v1\Note;
use App\Models\v1\User;

class NotesEndpointTest extends TestCase
{
    /**
     * @test
     */
    public function fetches_notes_from_url()
    {
        factory(Note::class, 2)->create();

        $this->get('/api/notes')
            ->seeJsonStructure([
                'data' => [
                    '*' => [
                        'subject',
                        'content',
                        'created_at'
                    ]
                ]
            ]);
    }

    /**
     * @test
     */
    public function filters_fetched_notes()
    {
        $note = factory(Note::class)->create();

        factory(Note::class)->create([
            'noteable_type' => 'users',
            'noteable_id' => function () {
                return factory(User::class)->create()->id;
            }
        ]);

        $this->get('/api/notes?type=reservations|' . $note->noteable->id)
            ->seeJson([
                'source' => [
                    'type' => 'reservations',
                    'id' => $note->noteable->id
                ]
            ])->dontSeeJson([
                'source' => [
                    'type' => 'reservations',
                    'id' => '*'
                ]
            ]);
    }

    /**
     * @test
     */
    public function user_is_returned_with_note()
    {
        factory(Note::class, 2)->create();

        $this->get('/api/notes?include=user')
            ->seeJsonStructure([
                'data' => [
                    '*' => [
                        'user' => [
                            'data' => [
                                'id', 'name'
                            ]
                        ]
                    ]
                ]
            ]);
    }

    /**
     * @test
     */
    public function note_request_validates()
    {
        $this->post('/api/notes')
             ->assertResponseStatus(422)
             ->seeJsonStructure([
                 'message',
                 'status_code',
                 'errors' => [
                     'user_id', 'subject', 'content',
                     'noteable_type', 'noteable_id'
                 ]
             ])->seeJson([
                 'status_code' => 422
             ]);
    }

    /**
     * @test
     */
    public function note_saves_in_database()
    {
        $note = factory(\App\Models\v1\Note::class)->make()->toArray();

        $this->post('/api/notes', $note)
            ->assertResponseOk()
            ->seeJson(
                array_except($note, ['id', 'noteable_type', 'noteable_id', 'user_id'])
            );
    }
}
