<?php

use Carbon\Carbon;
use App\Models\v1\Campaign;
use App\Models\v1\ActivityType;

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
                        'occurred_at',
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
                    'occurred_at',
                    'created_at',
                    'updated_at',
                    'deleted_at'
                ]
             ]);
    }

    /** @test */
    public function deletes_an_activity()
    {
        $activity = factory(App\Models\v1\Activity::class)->create();

        $this->delete('/api/activities/' . $activity->id)
             ->assertResponseStatus(204)
             ->dontSeeInDatabase('activities', ['id' => $activity->id, 'deleted_at' => null]);
    }
}
