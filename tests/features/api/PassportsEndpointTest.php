<?php

use App\Models\v1\User;
use App\Models\v1\Passport;

class PassportsEndpointTest extends BrowserKitTestCase
{

    /**
     * @test
     */
    public function returns_passports()
    {
        $passports = factory(Passport::class, 2)->create([
                'user_id' => function () {
                    return factory(User::class)->create()->id;
                }
            ]);

        $this->get('/api/passports')
            ->assertResponseOk()
            ->seeJsonStructure([
                'data' => [
                    '*' => [
                        'id', 'given_names', 'surname', 'number',
                        'birth_country', 'birth_country_name', 'citizenship',
                        'citizenship_name', 'upload_id', 'expires_at',
                        'created_at', 'updated_at', 'expired'
                    ]
                ]
            ]);
    }

    /**
     * @test
     */
    public function creates_passport()
    {
        $passport = factory(Passport::class)->make([
            'user_id' => function () {
                return factory(User::class)->create()->id;
            }
        ])->toArray();

        $this->post('/api/passports', $passport)
            ->assertResponseOk();
    }

    /**
     * @test
     */
    public function updates_passport()
    {
        $passport = factory(Passport::class)->create([
            'given_names' => 'joe smith',
            'user_id' => function () {
                return factory(User::class)->create()->id;
            },
        ]);

        $passport['given_names'] = 'john doe';

        $this->put('/api/passports/'.$passport->id, $passport->toArray())
            ->assertResponseOk()
            ->seeJson([
                'given_names' => 'john doe',
            ]);
    }
}
