<?php

use App\Models\v1\Cost;
use Illuminate\Database\Seeder;

class TestSiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //1n1d
        $oneNation = factory(App\Models\v1\Campaign::class, '1n1d2017')->create([
                'avatar_upload_id' => function () {
                    return factory(App\Models\v1\Upload::class, 'avatar')->create([
                        'name' => '1n1d17_white',
                        'source' => 'images/avatars/1n1d17-white-400x400.jpg'
                    ])->id;
                }
            ]);
        $oneNation->slug()->save(factory(App\Models\v1\Slug::class)->make([
                'url' => '1n1d2017'
            ]));

        factory(App\Models\v1\Trip::class, config('seeders.trips'))->create([
            'campaign_id' => $oneNation->id
        ])->each(function ($t) {

            $incrementalCosts = $t->costs()->saveMany([
                factory(App\Models\v1\Cost::class, 'super')->make(),
                factory(App\Models\v1\Cost::class, 'early')->make(),
                factory(App\Models\v1\Cost::class, 'general')->make()
            ]);

            collect($incrementalCosts)->each(function ($ic) {
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

            collect($optionalCosts)->each(function ($oc) use ($late) {
                Cost::findOrFail($oc->id)->payments()->save(
                    factory(App\Models\v1\Payment::class)->make([
                        'due_at' => $late->active_at,
                        'amount_owed' => $oc->amount,
                        'percent_owed' => 100,
                        'upfront' => false
                    ])
                );
            });

            $t->deadlines()->saveMany(factory(App\Models\v1\Deadline::class, 2)->make([
                    'date' => $t->started_at->subDays(random_int(7, 30))
                ]));

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

            $t->facilitators()->attach(factory(App\Models\v1\User::class)->create()->id);

            $t->interests()->saveMany(factory(App\Models\v1\TripInterest::class, 5)->make());

            factory(App\Models\v1\Reservation::class, config('seeders.reservations'))->create([
                'trip_id' => $t->id
            ])->each(function ($r) {
                $r->companions()->save(factory(App\Models\v1\Companion::class)->make());

                $r->notes()->save(factory(App\Models\v1\Note::class)->make());

                $tags = collect(['vip', 'missionary', 'medical', 'international', 'media', 'short'])
                    ->random(2)->all();

                $r->tag($tags);

                // $this->addDependencies($r);
            });
        });

        factory(App\Models\v1\User::class, 'admin')->create()->each(function ($user) {
            $user->slug()
                 ->save(factory(App\Models\v1\Slug::class)
                 ->make(['url' => str_slug($user->name)]));
            $user->assign('admin');
            $user->links()->saveMany([
                factory(App\Models\v1\Link::class)->make(['name' => 'facebook', 'url' => 'https://facebook.com']),
                factory(App\Models\v1\Link::class)->make(['name' => 'twitter', 'url' => 'https://twitter.com']),
                factory(App\Models\v1\Link::class)->make()
            ]);
            $user->stories()->saveMany(factory(App\Models\v1\Story::class, 3)->make(['author_id' => $user->id, 'author_type' => 'users']));
            $user->accolades()->save(factory(App\Models\v1\Accolade::class)->make());
            $user->accolades()->save(factory(App\Models\v1\Accolade::class, 'trip_history')->make());
            $user->essays()->save(factory(App\Models\v1\Essay::class)->make());
            $user->passports()->save(factory(App\Models\v1\Passport::class)->make());
            $user->visas()->save(factory(App\Models\v1\Visa::class)->make());
            $user->referrals()->save(factory(App\Models\v1\Referral::class)->make());
            $user->medicalReleases()->save(factory(App\Models\v1\MedicalRelease::class)->make());
        });
        


        // INDIA
        $india = factory(App\Models\v1\Campaign::class, 'india')->create([
                'avatar_upload_id' => function () {
                    return factory(App\Models\v1\Upload::class, 'avatar')->create([
                        'name' => 'angels_to_orphans',
                        'source' => 'images/avatars/orphansToAngelsSummer.jpg'
                    ])->id;
                }
            ]);
        $india->slug()->save(factory(App\Models\v1\Slug::class)->make([
                'url' => 'orphans2angels'
            ]));
    }

    private function addDependencies($res)
    {
        $active = $res->trip->activeCosts()->get();

        $maxDate = $active->whereStrict('type', 'incremental')->max('active_at');

        $incrementalCosts = $active->filter(function ($value) use ($maxDate) {
            return $value->type == 'incremental' && $value->active_at == $maxDate;
        });

        $staticCosts = $active->filter(function ($value) {
            return $value->type == 'static';
        });

        $optionalCosts = $active->filter(function ($value) {
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
            'amount' => 100,
            'fund_id' => $fund->id,
            'donor_id' => $donor->id
        ]);

        $balance = $transaction->fund->balance + $transaction->amount;
        $transaction->fund->balance = $balance;
        $transaction->fund->save();

        $transaction->fund->fundable
            ->payments()
            ->updateBalances($transaction->amount);

        $slug = str_slug(country($res->trip->country_code)).
            '-'.
            $res->trip->started_at->format('Y').
            '-'.
            str_random(4);

        $res->fund->fundraisers()->create([
            'name' => generateFundraiserName($res),
            'url' => $slug,
            'description' => file_get_contents(resource_path('assets/sample_fundraiser.md')),
            'sponsor_type' => 'users',
            'sponsor_id' => $res->user_id,
            'goal_amount' => $res->getTotalCost(),
            'started_at' => $res->created_at,
            'ended_at' => $res->trip->started_at
        ]);
    }
}
