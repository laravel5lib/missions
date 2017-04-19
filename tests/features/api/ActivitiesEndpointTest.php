<?php

class ActivitiesEndpointTest extends TestCase
{
    /** @test */
    public function gets_all_activities()
    {
        $activities = factory(App\Models\v1\Activity::class, 2)->create();

        $this->get('/api/activities')
             ->assertResponseOk()
             ->seeJsonStructure([
                'data' => [
                    '*' => [ 
                        'id',
                        'name',
                        'description',
                        'participant_id',
                        'participant_type',
                        'occured_at',
                        'created_at',
                        'updated_at',
                        'deleted_at'
                    ]
                ]
            ]);
    }

    /** @test */
    public function gets_activity_by_id()
    {
        $activity = factory(App\Models\v1\Activity::class)->create();

        $this->get('/api/activities/' . $activity->id)
             ->assertResponseOk()
             ->seeJson(['id' => $activity->id])
             ->seeJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'description',
                    'participant_id',
                    'participant_type',
                    'occured_at',
                    'created_at',
                    'updated_at',
                    'deleted_at'
                ]
            ]);
    }

    /** @test */
    public function creates_a_new_activity()
    {
        $data = factory(App\Models\v1\Activity::class)->make([
            'name' => 'Test Activity',
            'description' => 'A test activity submitted for testing purposes.',
        ])->toArray();

        $this->post('/api/activities', $data)
             ->assertResponseOk()
             ->SeeJson(['name' => 'Test Activity']);
    }

    /** @test */
    public function updates_an_activity()
    {
        $activity = factory(App\Models\v1\Activity::class)->create([
            'name' => 'Test Activity',
            'description' => 'A test activity submitted for testing purposes.',
        ]);

        $updates = ['name' => 'Updated Activity'];

        $this->put('/api/activities/' . $activity->id, $updates)
             ->assertResponseOk()
             ->SeeJson(['name' => 'Updated Activity']);
    }

    /** @test */
    public function deletes_an_activity()
    {
        $activity = factory(App\Models\v1\Activity::class)->create();

        $this->delete('/api/activities/' . $activity->id)
             ->assertResponseStatus(204)
             ->dontSeeInDatabase('activities', $activity->toArray());
    }
}
