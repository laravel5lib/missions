<?php

use Illuminate\Database\Seeder;

class ProjectTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\v1\ProjectCause::class, 'orphans')->create()->each(function($cause) {
            $cause->initiatives()->saveMany([
                factory(App\Models\v1\ProjectInitiative::class)->make([
                    'type' => '12 Child Home', 'country_code' => 'in', 
                    'project_cause_id' => $cause->id
                ]),
                factory(App\Models\v1\ProjectInitiative::class)->make([
                    'type' => '25 Child Home', 'country_code' => 'in',
                    'project_cause_id' => $cause->id
                ]),
                factory(App\Models\v1\ProjectInitiative::class)->make([
                    'type' => '50 Child Home', 'country_code' => 'in',
                    'project_cause_id' => $cause->id
                ]),
                factory(App\Models\v1\ProjectInitiative::class)->make([
                    'type' => 'Rescue Home', 'country_code' => 'np',
                    'project_cause_id' => $cause->id
                ]),
            ]);
        });

        factory(App\Models\v1\ProjectCause::class, 'water')->create()->each(function($cause) {
            $cause->initiatives()->saveMany([
                factory(App\Models\v1\ProjectInitiative::class)->make([
                    'type' => 'Fresh Water Well', 'country_code' => 'do',
                    'project_cause_id' => $cause->id
                ]),
                factory(App\Models\v1\ProjectInitiative::class)->make([
                    'type' => 'Fresh Water Well', 'country_code' => 'ni',
                    'project_cause_id' => $cause->id
                ]),
                factory(App\Models\v1\ProjectInitiative::class)->make([
                    'type' => 'Water Filtration System', 'country_code' => 'ni',
                    'project_cause_id' => $cause->id
                ])
            ]);
        });

        $causes = App\Models\v1\ProjectCause::all();

        $causes->each(function($cause) {

            $cause->initiatives->each(function($initiative) {
                factory(App\Models\v1\Project::class, 5)
                    ->create(['project_initiative_id' => $initiative->id]);
                    // ->each(function($project) use($staticCost) {
                    //     $project->costs()->sync([$staticCost->id]);
                    // });
            });

        });
    }
}
