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
            $cause->types()->saveMany([
                factory(App\Models\v1\ProjectType::class)->make(),
                factory(App\Models\v1\ProjectType::class)->make(),
                factory(App\Models\v1\ProjectType::class)->make()
            ]);
        });

        $causes->each(function($cause) {

            $cause->types->each(function($type) {
                $staticCost = $type->costs()->save(factory(App\Models\v1\Cost::class, 'static')->make());

                $staticCost->payments()->save(
                    factory(App\Models\v1\Payment::class)->make([
                        'due_at' => null,
                        'amount_owed' => $staticCost->amount / 2,
                        'percent_owed' => 50,
                        'upfront' => false
                    ])
                );

                $staticCost->payments()->save(
                    factory(App\Models\v1\Payment::class)->make([
                        'due_at' => null,
                        'amount_owed' => $staticCost->amount / 2,
                        'percent_owed' => 50,
                        'upfront' => false
                    ])
                );

                factory(App\Models\v1\Project::class, 5)
                    ->create(['project_type_id' => $type->id])
                    ->each(function($project) use($staticCost) {
                        $project->costs()->sync([$staticCost->id => ['quantity' => 1]]);
                    });
            });

        });
    }
}
