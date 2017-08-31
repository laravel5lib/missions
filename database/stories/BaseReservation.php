<?php

use App\Models\v1\Donor;
use App\Models\v1\Transaction;
use FactoryStories\FactoryStory;

class BaseReservation extends FactoryStory
{

    public function build($params = []){}

    protected function add_costs($res)
    {
        $fund = $res->fund()->create([
            'name' => generateFundName($res),
            'slug' => str_slug(generateFundName($res)),
            'balance' => 0,
            'class_id' => getAccountingClass($res)->id,
            'item_id'  => getAccountingItem($res)->id
        ]);

        $active = $res->trip->activeCosts()->get();

        $maxDate = $active->where('type', 'incremental')->max('active_at');

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

        $res->syncCosts($costs);
    }

    protected function add_requirements($res)
    {
        $res->syncRequirements($res->trip->requirements);
    }

    protected function add_todos($res)
    {
        $res->addTodos($res->trip->todos);
    }

    protected function add_deadlines($res)
    {
        $res->syncDeadlines($res->trip->deadlines);
    }

    protected function add_fundraiser($res)
    {
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

    protected function make_deposit($res)
    {
        $donor = factory(Donor::class)->create([
            'account_id' => $res->user_id,
            'account_type' => 'users',
            'name' => $res->user->name
        ]);

        $transaction = factory(Transaction::class)->create([
            'amount' => 100,
            'fund_id' => $res->fund->id,
            'donor_id' => $donor->id
        ]);

        $balance = $transaction->fund->balance + $transaction->amount;
        $transaction->fund->balance = $balance/100;
        $transaction->fund->save();

        $transaction->fund->fundable
            ->payments()
            ->updateBalances($transaction->amount);
    }

    protected function add_funds($reservation, $percentStart = 1, $percentEnd = 100)
    {
        // sum to a random percentage between $percentStart and $percenrtEnd of the total owed
        // $sum_to = $reservation->getTotalOwed() * round(mt_rand($percentStart, $percentEnd)/100);
        // $number_of_donations = mt_rand(2, 20);
        // $donations = [0];
        // $donation = 0;

        // // build array of random donation amounts
        // while(array_sum($donations) != $sum_to) {
        //     $donations[$donation] = mt_rand(0, $sum_to/mt_rand(1,5));

        //     if(++$donation == $number_of_donations) {
        //         $donation  = 0;
        //     }
        // }

        $donations = array_rand([
            1000 => 1000,
            500 => 500,
            100 => 100,
            50 => 50,
            20 => 20,
            10 => 10,
            5 => 5
        ], 3);

        // create donations from amounts
        foreach($donations as $amount) {
            $donor = (new RandomDonor)->create();

            $transaction = factory(Transaction::class)->create([
                'amount' => $amount,
                'fund_id' => $reservation->fund->id,
                'donor_id' => $donor->id,
                'anonymous' => array_rand([true => true, false => false])
            ]);

            $balance = $transaction->fund->balance + $transaction->amount;
            $transaction->fund->balance = $balance/100;
            $transaction->fund->save();

            $transaction->fund->fundable
                ->payments()
                ->updateBalances($transaction->amount);
        }
    }
}