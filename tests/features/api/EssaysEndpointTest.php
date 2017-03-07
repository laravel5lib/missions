<?php

use App\Models\v1\User;
use App\Models\v1\Essay;

class EssaysEndpointTest extends TestCase
{

    /**
     * @test
     */
    public function returns_essays()
    {
        $essays = factory(Essay::class, 2)->create([
                'user_id' => function () {
                    return factory(User::class)->create()->id;
                }
            ]);

        $this->get('/api/essays')
            ->assertResponseOk()
            ->seeJsonStructure([
                'data' => [
                    '*' => [
                        'id', 'user_id', 'author_name', 'subject', 'content',
                        'created_at', 'updated_at'
                    ]
                ]
            ]);
    }

    /**
     * @test
     */
    public function creates_essay()
    {
        $essay = factory(Essay::class)->make([
            'author_name' => 'joe',
            'user_id' => function() {
                return factory(User::class)->create()->id;
            }
        ])->toArray();

        $this->post('/api/essays', $essay)
            ->assertResponseOk()
            ->seeJson([
                'author_name' => 'joe'
            ]);
    }

    /**
     * @test
     */
    public function updates_essay()
    {
        $essay = factory(Essay::class)->create([
            'author_name' => 'joe',
            'user_id' => function() {
                return factory(User::class)->create()->id;
            }
        ]);

        $essay['author_name'] = 'john';

        $this->put('/api/essays/'.$essay->id, $essay->toArray())
            ->assertResponseOk()
            ->seeJson([
                'author_name' => 'john',
            ]);
    }
}