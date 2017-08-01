<?php

class ReportsEndpointTest extends TestCase
{
    /** @test */
    public function fetches_all_reports_by_user()
    {
        $user = factory(App\Models\v1\User::class)->create();
        $report = factory(App\Models\v1\Report::class)->create(['user_id' => $user->id]);

        $this->get('/api/users/'.$user->id.'/reports')
            ->assertResponseOk()
            ->seeJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'type',
                        'source',
                        'created_at'
                    ]
                ]
            ]);
    }
}
