<?php

use App\Models\v1\Cost;
use App\Models\v1\Fund;
use App\Models\v1\User;
use App\Models\v1\Project;
use App\Models\v1\Fundraiser;
use App\Models\v1\ProjectCause;
use App\Models\v1\ProjectInitiative;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectTest extends TestCase
{
    /**
     * @test
     */
    function update_fundraiser_goal_amount_on_cost_changes()
    {
        $project = factory(Project::class)->create([
            'project_initiative_id' => function () {
                return factory(ProjectInitiative::class)->create()->id;
            },
            'rep_id' => function () {
                return factory(User::class)->create()->id;
            },
            'sponsor_id' => function () {
                return factory(User::class)->create()->id;
            },
        ]);
        $fund = factory(Fund::class, 'project')->create([
            'fundable_id' => $project->id,
        ]);
        $fundraiser = factory(Fundraiser::class)->create([
            'fund_id' => $fund->id, 'goal_amount' => 0
        ]);
        $cost = factory(Cost::class, 'project')->create([
            'cost_assignable_id' => $project->id,
        ]);

        $this->assertSame($project->fundraisers()->first()->goal_amount, $project->goal);
    }
}
