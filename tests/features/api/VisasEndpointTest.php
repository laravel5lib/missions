<?php

use App\Models\v1\User;
use App\Models\v1\Visa;

class VisasEndpointTest extends TestCase
{

    /**
     * @test
     */
    public function returns_visas()
    {
        $visas = factory(Visa::class, 2)->create([
                'user_id' => function () {
                    return factory(User::class)->create()->id;
                }
            ]);

        $this->get('/api/visas')
            ->assertResponseOk()
            ->seeJsonStructure([
                'data' => [
                    '*' => [
                        'id', 'given_names', 'surname', 'number',
                        'country_code', 'country_name', 'upload_id',
                        'issued_at', 'expires_at', 'created_at',
                        'updated_at', 'expired'
                    ]
                ]
            ]);
    }

    /**
     * @test
     */
    public function creates_visa()
    {
        $visa = factory(Visa::class)->make([
            'user_id' => function () {
                return factory(User::class)->create()->id;
            }
        ])->toArray();

        $this->post('/api/visas', $visa)
            ->assertResponseOk();
    }

    /**
     * @test
     */
    public function updates_visa()
    {
        $visa = factory(Visa::class)->create([
            'given_names' => 'joe smith',
            'user_id' => function () {
                return factory(User::class)->create()->id;
            },
        ]);

        $visa['given_names'] = 'john doe';

        $this->put('/api/visas/'.$visa->id, $visa->toArray())
            ->assertResponseOk()
            ->seeJson([
                'given_names' => 'john doe',
            ]);
    }
}
