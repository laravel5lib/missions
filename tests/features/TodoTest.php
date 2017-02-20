<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TodoTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function fetches_todos_from_url()
    {
        $this->get('/api/todos')
            ->assertResponseOk()
            ->seeJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'task',
                        'completed_at'
                    ]
                ]
            ]);
    }

    /**
     * @test
     */
    public function filters_fetched_todos()
    {
        $reservation = factory(App\Models\v1\Reservation::class)->create();

        $reservation->todos()->save(factory(App\Models\v1\Todo::class)->make());

        $this->get('/api/todos?type=reservations|' . $reservation->id)
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
    public function user_is_returned_with_completed_todo()
    {
        $reservation = factory(App\Models\v1\Reservation::class)->create();

        $todo = $reservation->todos()->save(factory(App\Models\v1\Todo::class, 'completed')->make());

        $this->get('/api/todos/'.$todo->id.'?include=user')
            ->dontSeeJson([
                'completed_at' => null
            ])
            ->seeJsonStructure([
                'data' => [
                    'user' => [
                        'data' => [
                            'id', 'name'
                        ]
                    ]
                ]
            ]);
    }

    /**
     * @test
     */
    public function todo_request_validates()
    {
        $this->post('/api/todos', ['user_id' => 'foo', 'completed_at' => 'bar'])
            ->assertResponseStatus(422)
            ->seeJsonStructure([
                'message',
                'status_code',
                'errors' => [
                    'task', 'todoable_type', 'todoable_type',
                    'user_id', 'completed_at'
                ]
            ])->seeJson([
                'status_code' => 422
            ]);
    }

    /**
     * @test
     */
    public function todo_completes()
    {
        $reservation = factory(App\Models\v1\Reservation::class)->create();

        $todo = $reservation->todos()->save(factory(App\Models\v1\Todo::class)->make());

        $user = factory(App\Models\v1\User::class)->create();

        $this->put('/api/todos/'.$todo->id, ['user_id' => $user->id, 'complete' => true])
            ->assertResponseOk()
            ->seeInDatabase('todos', ['id' => $todo->id, 'user_id' => $user->id])
            ->dontSeeJson([
                'completed_at' => null
            ]);
    }

    /**
     * @test
     */
    public function can_reverse_todo_completion()
    {
        $reservation = factory(App\Models\v1\Reservation::class)->create();

        $todo = $reservation->todos()->save(factory(App\Models\v1\Todo::class, 'completed')->make());

        $this->put('/api/todos/'.$todo->id, ['complete' => false])
            ->assertResponseOk()
            ->seeInDatabase('todos', ['id' => $todo->id, 'user_id' => null])
            ->seeJson([
                'completed_at' => null
            ]);
    }
}
