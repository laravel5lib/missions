<?php

use App\Models\v1\Cost;
use App\Models\v1\User;
use App\Models\v1\Payment;
use App\Models\v1\Requirement;
use FactoryStories\FactoryStory;

class BaseTrip extends FactoryStory
{
    public function build($params = []){}

    protected function add_facilitators($trip)
    {
        // use a random user or create a new one
        $trip->facilitators()->attach(
            array_rand(User::pluck('id', 'id')->toArray()) ?: factory(User::class)->create()->id
        );
    }

    protected function add_incremental_costs($t)
    {
        $incrementalCosts = $t->costs()->saveMany([
            factory(Cost::class, 'super')->make([
                'cost_assignable_id' => $t->id,
                'active_at' => $t->started_at->subYear()
            ]),
            factory(Cost::class, 'early')->make([
                'cost_assignable_id' => $t->id,
                'active_at' => $t->started_at->subMonths(9)
            ]),
            factory(Cost::class, 'general')->make([
                'cost_assignable_id' => $t->id,
                'active_at' => $t->started_at->subMonths(6)
            ])
        ]);

        collect($incrementalCosts)->each(function($ic) {
            factory(Payment::class)->create([
                'cost_id' => $ic->id,
                'due_at' => $ic->active_at->addMonths(3),
                'amount_owed' => ($ic->amount/2)/100,
                'percent_owed' => 50,
                'upfront' => false
            ]);
            factory(Payment::class)->create([
                'cost_id' => $ic->id,
                'due_at' => $ic->active_at->addMonths(5)->addWeeks(2),
                'amount_owed' => ($ic->amount/2)/100,
                'percent_owed' => 50,
                'upfront' => false
            ]);
        });

        $late = $t->costs()->save(factory(Cost::class, 'late')->make([
            'cost_assignable_id' => $t->id,
            'active_at' => $t->started_at->subWeeks(2)
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

    protected function add_static_costs($t)
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

    protected function add_optional_costs($t, $late)
    {
        $options = [
            'double',
            'triple'
        ];

        $optionalCost = $t->costs()->save(
            factory(Cost::class, $options[array_rand($options)])
                ->make(['cost_assignable_id' => $t->id])
        );

        factory(Payment::class)->create([
            'cost_id' => $optionalCost->id,
            'due_at' => $late->active_at,
            'amount_owed' => $optionalCost->amount/100,
            'percent_owed' => 100,
            'upfront' => false
        ]);

    }

    protected function add_trip_requirements($trip)
    {
        $requirements = collect([
            factory(Requirement::class, 'passport')->make([
                'requester_id' => $trip->id, 'due_at' => $trip->started_at->subMonths(6)
            ]),
            factory(Requirement::class, 'medical')->make([
                'requester_id' => $trip->id, 'due_at' => $trip->started_at->subMonths(6)
            ]),
            factory(Requirement::class, 'referral')->make([
                'requester_id' => $trip->id, 'due_at' => $trip->started_at->subMonths(6)
            ]),
            factory(Requirement::class, 'testimony')->make([
                'requester_id' => $trip->id, 'due_at' => $trip->started_at->subMonths(6)
            ]),
            factory(Requirement::class, 'arrival')->make([
                'requester_id' => $trip->id, 'due_at' => $trip->started_at->subMonths(6)
            ]),
            factory(Requirement::class, 'travel-itinerary')->make([
                'requester_id' => $trip->id, 'due_at' => $trip->started_at->subMonths(6)
            ]),
        ])->toArray();

        Requirement::insert($requirements);
    }
}