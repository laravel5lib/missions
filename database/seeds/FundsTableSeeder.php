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
        factory(App\Models\v1\Transaction::class, 'donation', 100)->create();
        factory(App\Models\v1\Transaction::class, 'transfer_to', 25)->create();
        factory(App\Models\v1\Transaction::class, 'transfer_from', 25)->create();
    }
}
