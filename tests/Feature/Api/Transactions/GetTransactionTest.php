<?php

namespace Tests\Feature\Api\Transactions;

use Tests\TestCase;
use App\Models\v1\Fund;
use App\Models\v1\Donor;
use App\Models\v1\Transaction;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetTransactionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function gets_all_transactions()
    {
        factory(Transaction::class, 2)->create();

        $this->json('GET', '/api/transactions')
             ->assertStatus(200)
             ->assertJsonStructure([
                 'data' => [
                     ['id', 'type', 'amount', 'anonymous', 'description', 'details', 'created_at', 'updated_at']
                 ],
                 'meta'
             ])
             ->assertJson([
                 'meta' => ['total' => 2]
             ]);
    }

    /** @test */
    public function gets_donation_transactions()
    {
        $donation = factory(Transaction::class)->create();
        $credit = factory(Transaction::class, 'credit')->create();

        $this->json('GET', '/api/transactions?filter[type]=donation')
             ->assertStatus(200)
             ->assertJson([
                 'data' => [
                     ['id' => $donation->id, 'type' => 'donation']
                 ],
                 'meta' => ['total' => 1]
             ]);
    }

    /** @test */
    public function gets_transactions_about_amount()
    {
        $fiveHundred = factory(Transaction::class)->create(['amount' => 500]);
        $oneHundred = factory(Transaction::class)->create(['amount' => 100]);

        $this->json('GET', '/api/transactions?filter[amount]=10000')
             ->assertStatus(200)
             ->assertJson([
                 'data' => [
                     ['id' => $oneHundred->id, 'amount' => '100.00']
                 ],
                 'meta' => ['total' => 1]
             ]);
    }

    /** @test */
    public function gets_donations_by_donor_name()
    {
        $john = factory(Donor::class)->create(['first_name' => 'John', 'last_name' => 'Doe']);
        $johnsDonation = factory(Transaction::class)->create(['donor_id' => $john->id]);

        $joe = factory(Donor::class)->create(['first_name' => 'Joe', 'last_name' => 'Shmo']);
        $joesDonation = factory(Transaction::class)->create(['donor_id' => $joe->id]);

        $this->json('GET', '/api/transactions?filter[donor_name]=john+doe')
             ->assertStatus(200) 
             ->assertJson([
                'data' => [
                    ['id' => $johnsDonation->id],
                ],
                'meta' => ['total' => 1]
             ]);
    }

    /** @test */
    public function gets_transactions_by_fund_name()
    {
        $generalFund = factory(Fund::class)->create(['name' => 'General Fund']);
        $generalDonation = factory(Transaction::class)->create(['fund_id' => $generalFund->id]);

        $otherFund = factory(Fund::class)->create(['name' => 'Other Fund']);
        $otherDonation = factory(Transaction::class)->create(['fund_id' => $otherFund->id]);

        $this->json('GET', '/api/transactions?filter[fund_name]=general')
             ->assertStatus(200)
             ->assertJson([
                'data' => [
                    ['id' => $generalDonation->id],
                ],
                'meta' => ['total' => 1]
             ]);
    }

    /** @test */
    public function includes_fund_with_transactions()
    {
        factory(Transaction::class, 2)->create();

        $this->json('GET', '/api/transactions?include=fund')
             ->assertStatus(200)
             ->assertJsonStructure([
                'data' => [
                    [
                        'id', 'type', 'amount', 'anonymous', 'description', 
                        'details', 'created_at', 'updated_at', 
                        'fund' => [
                            'id', 'name', 'balance', 'type',
                            'class' => [
                                'id', 'name'
                            ],
                            'item' => [
                                'id', 'name'
                            ],
                            'slug', 'created_at', 'updated_at','deleted_at'
                        ]
                    ]
                ],
                'meta'
             ]);
    }

    /** @test */
    public function includes_donor_with_donations()
    {
        factory(Transaction::class, 2)->create();

        $this->json('GET', '/api/transactions?include=donor')
             ->assertStatus(200)
             ->assertJsonStructure([
                'data' => [
                    [
                        'id', 'type', 'amount', 'anonymous', 'description', 
                        'details', 'created_at', 'updated_at', 
                        'donor' => [
                            'id', 'name', 'first_name', 'last_name', 'company', 
                            'email', 'phone', 'address', 'city', 'state', 
                            'zip', 'country' => [
                                'code', 'name'
                            ],
                            'customer_id', 'created_at', 'updated_at', 'deleted_at'
                        ]
                    ]
                ],
                'meta'
             ]);
    }

    /** @test */
    public function gets_a_transaction_by_id()
    {
        $transaction = factory(Transaction::class)->create();

        $this->json('GET', "/api/transactions/$transaction->id")
             ->assertStatus(200)
             ->assertJson(['data' => ['id' => $transaction->id]])
             ->assertJsonStructure([
                'data' => [
                        'id', 'type', 'amount', 'anonymous', 'description', 
                        'details', 'created_at', 'updated_at', 
                        'fund' => [
                            'id', 'name', 'balance', 'type',
                            'class' => [
                                'id', 'name'
                            ],
                            'item' => [
                                'id', 'name'
                            ],
                            'slug', 'created_at', 'updated_at','deleted_at'
                        ],
                        'donor' => [
                            'id', 'name', 'first_name', 'last_name', 'company', 
                            'email', 'phone', 'address', 'city', 'state', 
                            'zip', 'country' => [
                                'code', 'name'
                            ],
                            'customer_id', 'created_at', 'updated_at', 'deleted_at'
                        ]
                ]
             ]);
    }
}
