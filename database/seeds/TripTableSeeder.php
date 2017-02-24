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
        $oneNation = factory(App\Models\v1\Campaign::class, '1n1d2017')->create([
                'avatar_upload_id' => function() {
                    return factory(App\Models\v1\Upload::class, 'avatar')->create([
                        'name' => '1n1d17_white',
                        'source' => 'images/avatars/1n1d17-white-400x400.jpg'
                    ])->id;
                }
            ]);

        $oneNation->slug()->save(factory(App\Models\v1\Slug::class)->make([
                'url' => '1n1d2017'
            ]));

        $groups = factory(App\Models\v1\Group::class, 10)->create();

        


        $groups->each(function($g) use($oneNation) {

            factory(App\Models\v1\Trip::class, 3)->create([
                    'campaign_id' => $oneNation->id,
                    'group_id' => $g->id
                ])->each(function($t) {

                $incrementalCosts = $t->costs()->saveMany([
                    factory(App\Models\v1\Cost::class, 'super')->make(['cost_assignable_id' => $t->id]),
                    factory(App\Models\v1\Cost::class, 'early')->make(['cost_assignable_id' => $t->id]),
                    factory(App\Models\v1\Cost::class, 'general')->make(['cost_assignable_id' => $t->id])
                ]);

                collect($incrementalCosts)->each(function($ic) {
                    Cost::findOrFail($ic->id)->payments()->saveMany([
                        factory(App\Models\v1\Payment::class)->make([
                            'cost_id' => $ic->id,
                            'due_at' => $ic->active_at->addMonths(6),
                            'amount_owed' => $ic->amount/2,
                            'percent_owed' => 50,
                            'upfront' => false
                        ]),
                        factory(App\Models\v1\Payment::class)->make([
                            'cost_id' => $ic->id,
                            'due_at' => $ic->active_at->addMonths(12),
                            'amount_owed' => $ic->amount/2,
                            'percent_owed' => 50,
                            'upfront' => false
                        ])
                    ]);
                });

                $late = $t->costs()->save(factory(App\Models\v1\Cost::class, 'late')->make(['cost_assignable_id' => $t->id]));
                $late->payments()->save(
                    factory(App\Models\v1\Payment::class)->make([
                        'cost_id' => $late->id,
                        'due_at' => $late->active_at,
                        'amount_owed' => $late->amount,
                        'percent_owed' => 100,
                        'upfront' => false
                    ])
                );

                $deposit = $t->costs()->save(factory(App\Models\v1\Cost::class, 'deposit')
                    ->make(['cost_assignable_id' => $t->id]));

                $deposit->payments()->save(
                    factory(App\Models\v1\Payment::class)->make([
                        'cost_id' => $deposit->id,
                        'due_at' => null,
                        'amount_owed' => $deposit->amount,
                        'percent_owed' => 100,
                        'upfront' => true
                    ])
                );

                $optionalCosts = $t->costs()->saveMany([
                    factory(App\Models\v1\Cost::class, 'double')->make(['cost_assignable_id' => $t->id]),
                    factory(App\Models\v1\Cost::class, 'triple')->make(['cost_assignable_id' => $t->id]),
                ]);

                collect($optionalCosts)->each(function($oc) use($late) {
                    Cost::findOrFail($oc->id)->payments()->save(
                        factory(App\Models\v1\Payment::class)->make([
                            'cost_id' => $oc->id,
                            'due_at' => $late->active_at,
                            'amount_owed' => $oc->amount,
                            'percent_owed' => 100,
                            'upfront' => false
                        ])
                    );
                });

                $t->deadlines()->saveMany(factory(App\Models\v1\Deadline::class, 2)->make([
                        'date' => $t->started_at->subDays(random_int(7, 30)),
                        'deadline_assignable_id' => $t->id
                    ]));

                $t->requirements()->saveMany([
                    factory(App\Models\v1\Requirement::class)->make([
                        'name' => 'Visa', 'document_type' => 'visas', 'requester_id' => $t->id
                    ]),
                    factory(App\Models\v1\Requirement::class)->make([
                        'name' => 'Passport', 'document_type' => 'passports', 'requester_id' => $t->id
                    ]),
                    factory(App\Models\v1\Requirement::class)->make([
                        'name' => 'Medical Release', 'document_type' => 'medical_releases', 'requester_id' => $t->id
                    ]),
                    factory(App\Models\v1\Requirement::class)->make([
                        'name' => 'Arrival Designation', 'document_type' => 'arrival_designations', 'requester_id' => $t->id
                    ]),
                    factory(App\Models\v1\Requirement::class)->make([
                        'name' => 'Testimony', 'document_type' => 'essays', 'requester_id' => $t->id
                    ]),
                    factory(App\Models\v1\Requirement::class)->make([
                        'name' => 'Pastoral Referral', 'document_type' => 'referrals', 'requester_id' => $t->id
                    ]),
                    factory(App\Models\v1\Requirement::class)->make([
                        'name' => 'Airport Preference', 'document_type' => 'airport_preferences', 'requester_id' => $t->id
                    ])
                ]);

                $t->notes()->save(factory(App\Models\v1\Note::class)->make(['noteable_id' => $t->id]));

                $t->facilitators()->attach(factory(App\Models\v1\User::class)->create()->id);

                $t->interests()->saveMany(factory(App\Models\v1\TripInterest::class, 10)->make(['trip_id' => $t->id]));

                factory(App\Models\v1\Reservation::class, 25)->create([
                    'trip_id' => $t->id
                ])->each(function($r) {
                    // $r->companions()->save(factory(App\Models\v1\Companion::class)->make());

                    $r->notes()->save(factory(App\Models\v1\Note::class)->make(['noteable_id' => $r->id]));

                    $tags = collect(['vip', 'missionary', 'medical', 'international', 'media', 'short'])
                        ->random(2)->all();

                    $r->tag($tags);

                    $this->addDependencies($r);
                });

            }); // end trips
        });
    }

    private function addDependencies($res)
    {
        $active = $res->trip->activeCosts()->get();

        $maxDate = $active->where('type', 'incremental')->max('active_at');

        $incrementalCosts = $active->filter(function ($value) use ($maxDate)
        {
            return $value->type == 'incremental' && $value->active_at == $maxDate;
        });

        $staticCosts = $active->filter(function ($value)
        {
            return $value->type == 'static';
        });

        $optionalCosts = $active->filter(function ($value)
        {
            return $value->type == 'optional';
        })->random(1);

        $costs = $incrementalCosts->merge($staticCosts)->push($optionalCosts);

        $fund = $res->fund()->create([
            'name' => generateFundName($res),
            'slug' => str_slug(generateFundName($res)),
            'balance' => 0,
            'class' => generateQbClassName($res),
            'item' => 'Missionary Donation'
        ]);

        $res->syncCosts($costs);
        $res->syncRequirements($res->trip->requirements);
        $res->syncDeadlines($res->trip->deadlines);
        $res->addTodos($res->trip->todos);

        $donor = factory(App\Models\v1\Donor::class)->create([
            'account_id' => $res->user_id,
            'account_type' => 'users',
            'name' => $res->user->name
        ]);

        $transaction = factory(App\Models\v1\Transaction::class)->create([
            'amount' => 10000,
            'fund_id' => $fund->id,
            'donor_id' => $donor->id
        ]);

        $balance = $transaction->fund->balance + $transaction->amount;
        $transaction->fund->balance = $balance;
        $transaction->fund->save();

        $transaction->fund->fundable
            ->payments()
            ->updateBalances($transaction->amount);

        $slug = str_slug(country($res->trip->country_code)).'-'.$res->trip->started_at->format('Y');

        $res->fund->fundraisers()->create([
            'name' => generateFundraiserName($res),
            'url' => generate_fundraiser_slug($slug),
            'description' => file_get_contents(resource_path('assets/sample_fundraiser.md')),
            'sponsor_type' => 'users',
            'sponsor_id' => $res->user_id,
            'goal_amount' => $res->getTotalCost(),
            'started_at' => $res->created_at,
            'ended_at' => $res->trip->started_at
        ]);
    }
}
