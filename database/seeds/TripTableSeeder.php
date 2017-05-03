<?php

use App\Models\v1\Cost;
use App\Models\v1\Note;
use App\Models\v1\Slug;
use App\Models\v1\Trip;
use App\Models\v1\User;
use App\Models\v1\Donor;
use App\Models\v1\Group;
use App\Models\v1\Upload;
use App\Models\v1\Payment;
use App\Models\v1\Campaign;
use App\Models\v1\Deadline;
use App\Models\v1\Companion;
use App\Models\v1\Requirement;
use App\Models\v1\Reservation;
use App\Models\v1\Transaction;
use App\Models\v1\TripInterest;
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
        $campaign = $this->seed_campaign();
        $groups = $this->seed_groups(10);

        $groups->each(function($g) use($campaign) {

            $trips = factory(Trip::class, 3)->create([
                'campaign_id' => $campaign->id,
                'group_id' => $g['id']
            ]);

            collect($trips)->each(function($t) use($g) {

                $t->rep->slug()->create(['url' => generate_slug($t->rep->name)]);

                $late = $this->add_incremental_costs($t);
                $this->add_static_costs($t);
                $this->add_optional_costs($t, $late);

                $deadlines = factory(Deadline::class, 2)->make([
                        'date' => $t->started_at->subDays(random_int(7, 30)),
                        'deadline_assignable_id' => $t->id
                    ])->toArray();
                Deadline::insert($deadlines);

                $this->add_trip_requirements($t);

                factory(Note::class)->create([
                    'noteable_type' => 'trips',
                    'noteable_id' => $t->id
                ]);

                $t->facilitators()->attach(
                    factory(User::class)->create()->id
                );

                $interests = factory(TripInterest::class, 10)->create(['trip_id' => $t->id]);

                $this->add_reservations($t);
            });
        });
    }

    private function seed_campaign()
    {
        $campaign = factory(Campaign::class, '1n1d2017')->create([
                'avatar_upload_id' => function () {
                    return factory(Upload::class, 'avatar')->create([
                        'name' => '1n1d17_white',
                        'source' => 'images/avatars/1n1d17-white-400x400.jpg'
                    ])->id;
                }
            ]);

        $campaign->slug()->create(['url' => '1n1d17']);

        return $campaign;
    }

    private function seed_groups(int $number)
    {
        $data = factory(Group::class, $number)->make();

        Group::insert($data->toArray());

        $groups = Group::whereIn('id', $data->pluck('id'))->get();

        collect($groups)->each(function($g) {
            $g->slug()->create(['url' => generate_slug($g->name)]);
        });

        return collect($groups);
    }

    private function add_incremental_costs($t)
    {
        $incrementalCosts = $t->costs()->saveMany([
            factory(Cost::class, 'super')->make([
                'cost_assignable_id' => $t->id
            ]),
            factory(App\Models\v1\Cost::class, 'early')->make([
                'cost_assignable_id' => $t->id
            ]),
            factory(App\Models\v1\Cost::class, 'general')->make([
                'cost_assignable_id' => $t->id
            ])
        ]);

        collect($incrementalCosts)->each(function($ic) {
            Cost::findOrFail($ic->id)->payments()->saveMany([
                factory(Payment::class)->make([
                    'cost_id' => $ic->id,
                    'due_at' => $ic->active_at->addMonths(6),
                    'amount_owed' => ($ic->amount/2)/100,
                    'percent_owed' => 50,
                    'upfront' => false
                ]),
                factory(Payment::class)->make([
                    'cost_id' => $ic->id,
                    'due_at' => $ic->active_at->addMonths(12),
                    'amount_owed' => ($ic->amount/2)/100,
                    'percent_owed' => 50,
                    'upfront' => false
                ])
            ]);
        });

        $late = $t->costs()->save(factory(Cost::class, 'late')->make([
            'cost_assignable_id' => $t->id
        ]));
        $late->payments()->save(
            factory(Payment::class)->make([
                'cost_id' => $late->id,
                'due_at' => $late->active_at,
                'amount_owed' => $late->amount/100,
                'percent_owed' => 100,
                'upfront' => false
            ])
        );

        return $late;
    }

    private function add_static_costs($t)
    {
        $deposit = $t->costs()->save(factory(Cost::class, 'deposit')
            ->make(['cost_assignable_id' => $t->id]));

        $deposit->payments()->save(
            factory(Payment::class)->make([
                'cost_id' => $deposit->id,
                'due_at' => null,
                'amount_owed' => $deposit->amount/100,
                'percent_owed' => 100,
                'upfront' => true
            ])
        );
    }

    private function add_optional_costs($t, $late)
    {
        $optionalCosts = $t->costs()->saveMany([
            factory(Cost::class, 'double')->make(['cost_assignable_id' => $t->id]),
            factory(Cost::class, 'triple')->make(['cost_assignable_id' => $t->id]),
        ]);

        collect($optionalCosts)->each(function($oc) use($late) {
            Cost::findOrFail($oc->id)->payments()->save(
                factory(Payment::class)->make([
                    'cost_id' => $oc->id,
                    'due_at' => $late->active_at,
                    'amount_owed' => $oc->amount/100,
                    'percent_owed' => 100,
                    'upfront' => false
                ])
            );
        });
    }

    private function add_trip_requirements($t)
    {
        $requirements = collect([
            factory(Requirement::class, 'visa')->make(['requester_id' => $t->id]),
            factory(Requirement::class, 'passport')->make(['requester_id' => $t->id]),
            factory(Requirement::class, 'medical')->make(['requester_id' => $t->id]),
            factory(Requirement::class, 'referral')->make(['requester_id' => $t->id]),
            factory(Requirement::class, 'testimony')->make(['requester_id' => $t->id]),
            factory(Requirement::class, 'arrival')->make(['requester_id' => $t->id]),
            factory(Requirement::class, 'airport')->make(['requester_id' => $t->id]),
            factory(Requirement::class, 'media-credentials')->make(['requester_id' => $t->id]),
            factory(Requirement::class, 'medical-credentials')->make(['requester_id' => $t->id]),
            factory(Requirement::class, 'influencer-application')->make(['requester_id' => $t->id]),
            factory(Requirement::class, 'travel-itinerary')->make(['requester_id' => $t->id]),
        ])->toArray();

        Requirement::insert($requirements);
    }

    private function add_reservations($t)
    {
        $data = factory(Reservation::class, 25)->make(['trip_id' => $t->id]);
        Reservation::insert($data->toArray());

        $reservations = Reservation::whereIn('id', $data->pluck('id'))->with('trip')->get();
        
        $reservations->each(function ($r) use ($reservations) {
            
            $r->user->slug()->create(['url' => generate_slug($r->user->name)]);

            $companion = factory(Companion::class)->make([
                'reservation_id' => $r->id,
                'companion_id' => $reservations->random()->pluck('id')->first()
            ])->toArray();
            Companion::insert($companion);

            $note = factory(Note::class)->make([
                'noteable_id' => $r->id, 
                'user_id' => $r->trip->rep_id
            ])->toArray();
            Note::insert($note);

            $tags = collect(['vip', 'missionary', 'medical', 'international', 'media', 'short'])
                ->random(2)->all();

            $r->tag($tags);

            $this->addDependencies($r);
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

        $donor = factory(Donor::class)->create([
            'account_id' => $res->user_id,
            'account_type' => 'users',
            'name' => $res->user->name
        ]);

        $transaction = factory(Transaction::class)->create([
            'amount' => 100,
            'fund_id' => $fund->id,
            'donor_id' => $donor->id
        ]);

        $balance = $transaction->fund->balance + $transaction->amount;
        $transaction->fund->balance = $balance/100;
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
            'goal_amount' => $res->getTotalCost()/100,
            'started_at' => $res->created_at,
            'ended_at' => $res->trip->started_at
        ]);
    }
}
