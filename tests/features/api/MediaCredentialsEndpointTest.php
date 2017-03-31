<?php

class MediaCredentialsEndpointTest extends TestCase
{

    /**
     * @test
     */
    public function fetches_media_credentials()
    {
        $credential = factory(App\Models\v1\Credential::class, 'media')->create();

        $this->get('/api/credentials/media')
             ->assertResponseOk()
             ->seeJsonStructure([
                'data' => [
                    '*' => [
                        'id', 'type', 'holder_id', 'holder_type', 'applicant_name',
                        'content', 'expired_at', 'created_at', 'updated_at', 'deleted_at'
                    ]
                ]
            ])
             ->seeJson(['type' => 'media']);
    }

    /** @test */
    public function fetches_media_credential_by_id()
    {
        $credential = factory(App\Models\v1\Credential::class, 'media')->create();

        $this->get('/api/credentials/media/' . $credential->id)
             ->assertResponseOk()
             ->seeJson(['id' => $credential->id]);
    }

    /** @test */
    public function creates_media_credential()
    {
        $credential = factory(App\Models\v1\Credential::class, 'media')
                          ->make(['applicant_name' => 'Joe'])
                          ->toArray();

        $this->post('/api/credentials/media/', $credential)
             ->assertResponseOk()
             ->seeJson(['applicant_name' => 'Joe']);
    }

    /** @test */
    public function updates_media_credential()
    {
        $credential = factory(App\Models\v1\Credential::class, 'media')
                          ->create(['applicant_name' => 'John']);

        $this->put('/api/credentials/media/'.$credential->id, ['applicant_name' => 'Joe'])
             ->assertResponseOk()
             ->seeJson(['applicant_name' => 'Joe']);
    }

    /** @test */
    public function deletes_media_credential()
    {
        $credential = factory(App\Models\v1\Credential::class, 'media')->create();

        $this->delete('/api/credentials/media/'.$credential->id)
             ->assertResponseStatus(204);
    }
}