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
        $causes = factory(App\Models\v1\ProjectCause::class, 2)->create()->each(function($cause) {
            $cause->initiatives()->saveMany([
                factory(App\Models\v1\ProjectInitiative::class)->make(),
                factory(App\Models\v1\ProjectInitiative::class)->make(),
                factory(App\Models\v1\ProjectInitiative::class)->make()
            ]);
        });

        $causes->each(function($cause) {

            $cause->initiatives->each(function($initiative) {
                $staticCost = $initiative->costs()->save(factory(App\Models\v1\Cost::class, 'static')->make());

                $staticCost->payments()->save(
                    factory(App\Models\v1\Payment::class)->make([
                        'amount_owed' => $staticCost->amount / 2,
                        'percent_owed' => 50,
                        'upfront' => false
                    ])
                );

                $staticCost->payments()->save(
                    factory(App\Models\v1\Payment::class)->make([

                        'amount_owed' => $staticCost->amount / 2,
                        'percent_owed' => 50,
                        'upfront' => false
                    ])
                );

                factory(App\Models\v1\Project::class, 5)
                    ->create(['project_initiative_id' => $initiative->id])
                    ->each(function($project) use($staticCost) {
                        $project->costs()->sync([$staticCost->id => ['quantity' => 1]]);
                    });
            });

        });
    }
}
