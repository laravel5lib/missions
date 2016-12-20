<?php

use Illuminate\Database\Seeder;

class FundsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\v1\Fund::class)->create([
            'name' => 'Missions.Me',
            'balance' => 0,
            'class' => 'General',
            'item' => 'General Donation',
            'fundable_type' => 'groups',
            'fundable_id' => function () {
                return factory(App\Models\v1\Group::class)->create([
                    'name' => 'Missions.Me'
                ])->id;
            }
        ]);

        factory(App\Models\v1\Transaction::class, 'donation', 100)->create()
            ->each(function($t) {
                $balance = $t->fund->balance + $t->amount;
                $t->fund->balance = $balance;
                $t->fund->save();
            });
        factory(App\Models\v1\Transaction::class, 'check', 100)->create()
            ->each(function($t) {
                $balance = $t->fund->balance + $t->amount;
                $t->fund->balance = $balance;
                $t->fund->save();
            });
        factory(App\Models\v1\Transaction::class, 'transfer_to', 25)->create()
            ->each(function($t) {
                $balance = $t->fund->balance + $t->amount;
                $t->fund->balance = $balance;
                $t->fund->save();
            });
        factory(App\Models\v1\Transaction::class, 'transfer_from', 25)->create()
            ->each(function($t) {
                $balance = $t->fund->balance + $t->amount;
                $t->fund->balance = $balance;
                $t->fund->save();
            });
    }
}
