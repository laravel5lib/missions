<?php

class PlansEndpointTest extends BrowserKitTestCase
{
    use AuthenticatedUserSetup;

    /** @test */
    public function gets_all_plans()
    {
        factory(App\Models\v1\RoomingPlan::class, 2)->create();

        $this->get('api/rooming/plans')
            ->assertResponseOk()
            ->seeJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'short_desc',
                        'room_types',
                        'rooms_count',
                        'occupants_count',
                        'created_at',
                        'updated_at',
                        'deleted_at'
                    ]
                ]
            ]);
    }

    /** @test */
    public function get_specific_plan()
    {
        $plan = factory(App\Models\v1\RoomingPlan::class)->create([
            'name' => 'Test Plan',
            'short_desc' => 'Test'
        ]);

        $this->get('api/rooming/plans/' . $plan->id)
            ->assertResponseOk()
            ->seeJson([
                'name' => 'Test Plan',
                'short_desc' => 'Test'
            ]);
    }

    /** @test */
    public function create_plan()
    {
        $campaign = factory(App\Models\v1\Campaign::class)->create();
        $groups = factory(App\Models\v1\Group::class, 2)->create();

        $plan = [
            'name' => 'Test Plan',
            'short_desc' => 'Test',
            'campaign_id' => $campaign->id,
            'group_ids' => $groups->pluck('id')->all()
        ];

        $this->post('/api/rooming/plans', $plan)
            ->assertResponseOk()
            ->seeJson([
                'name' => 'Test Plan',
                'short_desc' => 'Test'
            ])
            ->seeInDatabase('rooming_plans', ['name' => 'Test Plan', 'campaign_id' => $campaign->id])
            ->seeInDatabase('rooming_plan_group', ['group_id' => $groups->pluck('id')->first()]);
    }

    /** @test */
    public function update_plan()
    {
        $groups = factory(App\Models\v1\Group::class, 2)->create();

        $plan = factory(App\Models\v1\RoomingPlan::class)->create([
            'name' => 'Created Plan'
        ]);
        $plan->groups()->sync($groups->pluck('id')->all());

        $changes = ['name' => 'Updated Plan'];

        $this->put('/api/rooming/plans/' . $plan->id, $changes)
            ->assertResponseOk()
            ->seeJson(['name' => 'Updated Plan'])
            ->seeInDatabase('rooming_plans', ['name' => 'Updated Plan'])
            ->dontSeeInDatabase('rooming_plan_group', ['group_id' => $groups->pluck('id')->first()]);
    }

    public function delete_plan()
    {
        $groups = factory(App\Models\v1\Group::class, 2)->create();
        $plan = factory(App\Models\v1\RoomingPlan::class)->create([
            'name' => 'Test Plan'
        ]);
        $plan->groups()->sync($groups->pluck('id')->all());

        $this->delete('/api/rooming/plans/' . $plan->id)
            ->assertResponseOk()
            ->dontSeeInDatabase('rooming_plans', ['name' => 'Test Plan', 'deleted_at' => null])
            ->seeInDatabase('rooming_plan_group', ['group_id' => $groups->pluck('id')->first()]);
    }
}
