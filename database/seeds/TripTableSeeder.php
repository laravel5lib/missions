<?php

use App\Models\v1\Cost;
use App\Models\v1\Payment;
use App\Models\v1\User;
use Illuminate\Database\Seeder;

class TripTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\v1\Trip::class, config('seeders.trips'))->create()->each(function($t) {

            $incrementalCosts = $t->costs()->saveMany(factory(App\Models\v1\Cost::class, 'incremental', 3)->make());

            $incrementalCosts->each(function($ic) {
                Cost::findOrFail($ic->id)->payments()->saveMany([
                    factory(App\Models\v1\Payment::class)->make([
                        'due_at' => $ic->active_at->addMonths(6),
                        'amount_owed' => $ic->amount/2,
                        'percent_owed' => 50,
                        'upfront' => false
                    ]),
                    factory(App\Models\v1\Payment::class)->make([
                        'due_at' => $ic->active_at->addMonths(12),
                        'amount_owed' => $ic->amount/2,
                        'percent_owed' => 50,
                        'upfront' => false
                    ])
                ]);
            });

            $staticCost = $t->costs()->save(factory(App\Models\v1\Cost::class, 'static')->make());

            $staticCost->payments()->save(
                factory(App\Models\v1\Payment::class)->make([
                    'due_at' => null,
                    'amount_owed' => $staticCost->amount,
                    'percent_owed' => 100,
                    'upfront' => true
                ])
            );

            $t->deadlines()->saveMany(factory(App\Models\v1\Deadline::class, 2)->make());

            $t->requirements()->saveMany(factory(App\Models\v1\Requirement::class, 4)->make());

            $t->notes()->save(factory(App\Models\v1\Note::class)->make());

            $t->facilitators()->attach(User::where('email', 'admin@admin.com')->first()->id);

            $t->interests()->saveMany(factory(App\Models\v1\TripInterest::class, 5)->make());

        });
    }
}
