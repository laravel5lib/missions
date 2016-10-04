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
        factory(App\Models\v1\ProjectType::class, 8)->create();
        factory(App\Models\v1\Cause::class, 5)->create()->each(function($cause) {
            $cause->initiatives()->saveMany([
                factory(App\Models\v1\ProjectInitiative::class)->make(),
                factory(App\Models\v1\ProjectInitiative::class)->make(),
                factory(App\Models\v1\ProjectInitiative::class)->make()
            ]);
        });
        factory(App\Models\v1\ProjectPackage::class, 20)->create()->each(function($package) {

            $staticCost = $package->costs()->save(factory(App\Models\v1\Cost::class, 'static')->make());

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
                ->create(['project_package_id' => $package->id])
                ->each(function($project) use($staticCost) {
                    $project->costs()->sync([$staticCost->id => ['quantity' => 1]]);
                });
        });
    }
}
