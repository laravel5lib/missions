<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NoteTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function fetches_notes_from_url()
    {
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
        $reservation = factory(App\Models\v1\Reservation::class)->create();

        $reservation->notes()->save(factory(App\Models\v1\Note::class)->make());

        $this->get('/api/notes?type=reservations|' . $reservation->id)
            ->seeJson([
                'source' => [
                    'type' => 'reservations',
                    'id' => $reservation->id
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
            ->seeInDatabase('notes', $note)
            ->assertResponseOk()
            ->seeJson(
                array_except($note, ['noteable_type', 'noteable_id', 'user_id'])
            );
    }

}
