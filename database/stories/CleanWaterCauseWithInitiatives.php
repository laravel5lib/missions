<?php

use App\Models\v1\ProjectCause;
use FactoryStories\FactoryStory;
use App\Models\v1\ProjectInitiative;

class CleanWaterCauseWithInitiatives extends FactoryStory
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
        $cause = factory(ProjectCause::class, 'water')->create();

        $cause->each(function ($water) {
            $water->initiatives()->saveMany([
                factory(ProjectInitiative::class)->make([
                    'type' => 'Fresh Water Well', 'country_code' => 'do',
                    'project_cause_id' => $water->id
                ]),
                factory(ProjectInitiative::class)->make([
                    'type' => 'Fresh Water Well', 'country_code' => 'ni',
                    'project_cause_id' => $water->id
                ]),
                factory(ProjectInitiative::class)->make([
                    'type' => 'Water Filtration System', 'country_code' => 'ni',
                    'project_cause_id' => $water->id
                ])
            ]);
        });

        return $cause;
    }
}
