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

}
