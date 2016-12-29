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

            $incrementalCosts = $t->costs()->saveMany([
                factory(App\Models\v1\Cost::class, 'super')->make(),
                factory(App\Models\v1\Cost::class, 'early')->make(),
                factory(App\Models\v1\Cost::class, 'general')->make()
            ]);

            collect($incrementalCosts)->each(function($ic) {
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

            $late = $t->costs()->save(factory(App\Models\v1\Cost::class, 'late')->make());
            $late->payments()->save(
                factory(App\Models\v1\Payment::class)->make([
                    'due_at' => $late->active_at,
                    'amount_owed' => $late->amount,
                    'percent_owed' => 100,
                    'upfront' => false
                ])
            );

            $deposit = $t->costs()->save(factory(App\Models\v1\Cost::class, 'deposit')->make());

            $deposit->payments()->save(
                factory(App\Models\v1\Payment::class)->make([
                    'due_at' => null,
                    'amount_owed' => $deposit->amount,
                    'percent_owed' => 100,
                    'upfront' => true
                ])
            );

            $optionalCosts = $t->costs()->saveMany([
                factory(App\Models\v1\Cost::class, 'double')->make(),
                factory(App\Models\v1\Cost::class, 'triple')->make(),
            ]);

            collect($optionalCosts)->each(function($oc) use($late) {
                Cost::findOrFail($oc->id)->payments()->save(
                    factory(App\Models\v1\Payment::class)->make([
                        'due_at' => $late->active_at,
                        'amount_owed' => $oc->amount,
                        'percent_owed' => 100,
                        'upfront' => false
                    ])
                );
            });

            $t->deadlines()->saveMany(factory(App\Models\v1\Deadline::class, 2)->make());

            $t->requirements()->saveMany([
                factory(App\Models\v1\Requirement::class)->make(['name' => 'Visa', 'document_type' => 'visas']),
                factory(App\Models\v1\Requirement::class)->make(['name' => 'Passport', 'document_type' => 'passports']),
                factory(App\Models\v1\Requirement::class)->make(['name' => 'Medical Release', 'document_type' => 'medical_releases']),
                factory(App\Models\v1\Requirement::class)->make(['name' => 'Arrival Designation', 'document_type' => 'arrival_designations']),
                factory(App\Models\v1\Requirement::class)->make(['name' => 'Testimony', 'document_type' => 'essays']),
                factory(App\Models\v1\Requirement::class)->make(['name' => 'Pastoral Referral', 'document_type' => 'referrals']),
                factory(App\Models\v1\Requirement::class)->make(['name' => 'Airport Preference', 'document_type' => 'airport_preferences'])
            ]);

            $t->notes()->save(factory(App\Models\v1\Note::class)->make());

            $t->facilitators()->attach(User::where('email', 'admin@admin.com')->first()->id);

            $t->interests()->saveMany(factory(App\Models\v1\TripInterest::class, 5)->make());

        });
    }
}
