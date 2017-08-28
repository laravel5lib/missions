<?php

use App\Models\v1\Cost;
use App\Models\v1\User;
use App\Models\v1\Payment;
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
        factory(App\Models\v1\ProjectCause::class, 'orphans')->create()->each(function ($orphan) {
            $orphan->initiatives()->saveMany([
                factory(App\Models\v1\ProjectInitiative::class)->make([
                    'type' => '12 Child Home', 'country_code' => 'in',
                    'project_cause_id' => $orphan->id
                ]),
                factory(App\Models\v1\ProjectInitiative::class)->make([
                    'type' => '25 Child Home', 'country_code' => 'in',
                    'project_cause_id' => $orphan->id
                ]),
                factory(App\Models\v1\ProjectInitiative::class)->make([
                    'type' => '50 Child Home', 'country_code' => 'in',
                    'project_cause_id' => $orphan->id
                ]),
                factory(App\Models\v1\ProjectInitiative::class)->make([
                    'type' => 'Rescue Home', 'country_code' => 'np',
                    'project_cause_id' => $orphan->id
                ]),
            ]);
        });

        factory(App\Models\v1\ProjectCause::class, 'water')->create()->each(function ($water) {
            $water->initiatives()->saveMany([
                factory(App\Models\v1\ProjectInitiative::class)->make([
                    'type' => 'Fresh Water Well', 'country_code' => 'do',
                    'project_cause_id' => $water->id
                ]),
                factory(App\Models\v1\ProjectInitiative::class)->make([
                    'type' => 'Fresh Water Well', 'country_code' => 'ni',
                    'project_cause_id' => $water->id
                ]),
                factory(App\Models\v1\ProjectInitiative::class)->make([
                    'type' => 'Water Filtration System', 'country_code' => 'ni',
                    'project_cause_id' => $water->id
                ])
            ]);
        });

        $causes = App\Models\v1\ProjectCause::all();

        $causes->each(function ($cause) {

            $cause->initiatives->each(function ($initiative) {
                factory(App\Models\v1\Project::class, 5)
                    ->create([
                        'project_initiative_id' => $initiative->id,
                        'sponsor_id' => function () {
                            return factory(User::class)->create()->id;
                        }
                    ])->each(function ($project) {
                        $project->sponsor->slug()->create(['url' => generate_slug($project->sponsor->name)]);
                        
                        $cost = factory(Cost::class, 'project')->create([
                                    'cost_assignable_id' => $project->id
                                ]);

                        $cost->payments()->saveMany([
                            factory(Payment::class)->make([
                                'cost_id' => $cost->id,
                                'amount_owed' => ($cost->amount/100)/2,
                                'percent_owed' => 50,
                                'upfront' => false,
                            ]),
                            factory(Payment::class)->make([
                                'cost_id' => $cost->id,
                                'amount_owed' => ($cost->amount/100)/2,
                                'percent_owed' => 50,
                                'upfront' => false,
                            ])
                        ]);
                    });
            });
        });
    }
}
