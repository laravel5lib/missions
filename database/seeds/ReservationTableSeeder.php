<?php

use Illuminate\Database\Seeder;

class ReservationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\v1\Reservation::class, config('seeders.reservations'))->create()->each(function($r)
        {

            $r->companions()->save(factory(App\Models\v1\Companion::class)->make());

            $r->notes()->save(factory(App\Models\v1\Note::class)->make());

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

        $costs = $active->reject(function ($value) use ($maxDate)
        {
            return $value->type == 'incremental' && $value->active_at < $maxDate;
        });

        $fund = $res->fund()->create([
            'name' => generateFundName($res),
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

        $transaction = factory(App\Models\v1\Transaction::class, 'donation')->create([
            'amount' => 100,
            'description' => 'Deposit toward ' . $fund->name,
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
