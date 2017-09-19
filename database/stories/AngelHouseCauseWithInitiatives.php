<?php

use App\Models\v1\ProjectCause;
use FactoryStories\FactoryStory;
use App\Models\v1\ProjectInitiative;

class AngelHouseCauseWithInitiatives extends FactoryStory
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
        $cause = factory(ProjectCause::class, 'orphans')->create();

        $cause->each(function ($orphan) {
            $orphan->initiatives()->saveMany([
                factory(ProjectInitiative::class)->make([
                    'type' => '12 Child Home', 'country_code' => 'in',
                    'project_cause_id' => $orphan->id
                ]),
                factory(ProjectInitiative::class)->make([
                    'type' => '25 Child Home', 'country_code' => 'in',
                    'project_cause_id' => $orphan->id
                ]),
                factory(ProjectInitiative::class)->make([
                    'type' => '50 Child Home', 'country_code' => 'in',
                    'project_cause_id' => $orphan->id
                ])
            ]);
        });

        return $cause;
    }
}
