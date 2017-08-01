<?php

class MedicalCredentialsEndpointTest extends TestCase
{

    /**
     * @test
     */
    public function fetches_medical_credentials()
    {
        $credential = factory(App\Models\v1\Credential::class, 'medical')->create();

        $this->get('/api/credentials/medical')
             ->assertResponseOk()
             ->seeJsonStructure([
                'data' => [
                    '*' => [
                        'id', 'type', 'holder_id', 'holder_type', 'applicant_name',
                        'content', 'expired_at', 'created_at', 'updated_at', 'deleted_at'
                    ]
                ]
             ]);
    }

    /** @test */
    public function fetches_medical_credential_by_id()
    {
        $credential = factory(App\Models\v1\Credential::class, 'medical')->create();

        $this->get('/api/credentials/medical/' . $credential->id)
             ->assertResponseOk()
             ->seeJson(['id' => $credential->id]);
    }

    /** @test */
    public function creates_medical_credential()
    {
        $credential = factory(App\Models\v1\Credential::class, 'medical')
                          ->make(['applicant_name' => 'Joe'])
                          ->toArray();

        $this->post('/api/credentials/medical/', $credential)
             ->assertResponseOk()
             ->seeJson(['applicant_name' => 'Joe']);
    }

    /** @test */
    public function updates_medical_credential()
    {
        $credential = factory(App\Models\v1\Credential::class, 'medical')
                          ->create(['applicant_name' => 'John']);

        $this->put('/api/credentials/medical/'.$credential->id, ['applicant_name' => 'Joe'])
             ->assertResponseOk()
             ->seeJson(['applicant_name' => 'Joe']);
    }

    /** @test */
    public function deletes_medical_credential()
    {
        $credential = factory(App\Models\v1\Credential::class, 'medical')->create();

        $this->delete('/api/credentials/medical/'.$credential->id)
             ->assertResponseStatus(204);
    }
}
