<?php

use App\Models\v1\Cost;
use App\Models\v1\User;
use App\Models\v1\Payment;
use App\Models\v1\Project;
use App\Models\v1\ProjectCause;
use FactoryStories\FactoryStory;

class ActiveProject extends FactoryStory
{
    /**
     * Here you can create your complex model factory
     * logic
     *
     * @param array $params Array of params
     *
     * @return Mixed
     */
    public function build($params = [])
    {
        $cause = $this->getCause($params);

        factory(Project::class)
            ->create([
                'project_initiative_id' => $this->randomInitiativeId($cause),
                'sponsor_id' => $this->randomSponsorId(),
            ])->each(function ($project) {
                $project->sponsor->slug()
                    ->create(['url' => generate_slug($project->sponsor->name)]);

                $cost = factory(Cost::class, 'project')->create([
                            'cost_assignable_id' => $project->id
                        ]);

                $cost->payments()->saveMany([
                    $this->makePayment($cost),
                    $this->makePayment($cost)
                ]);
            });
    }

    private function getCause($params = [])
    {
        if (isset($params['cause_id']) && ProjectCause::find($params['cause_id'])) {
            return ProjectCause::find($params['cause_id']);
        }

        return (new AngelHouseCauseWithInitiatives)->create();
    }

    private function randomInitiativeId($cause)
    {
        return array_rand(
            $cause->initiatives->pluck('id', 'id')->toArray()
        );
    }

    private function randomSponsorId()
    {
        return array_rand(
            User::pluck('id', 'id')->toArray()
        ) ?: (new NewUser)->create()->id;
    }

    private function makePayment($cost)
    {
        return factory(Payment::class)->make([
            'cost_id' => $cost->id,
            'amount_owed' => ($cost->amount/100)/2,
            'percent_owed' => 50,
            'upfront' => false,
        ]);
    }
}
