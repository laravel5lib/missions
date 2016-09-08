<?php

use App\Events\ReservationWasCreated;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;

class ReservationPaymentTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_updates_a_fund_balance()
    {
        $reservation = $this->makeReservation();
        $transaction = $this->makeDonation([
            'fund_id' => $reservation->fund->id,
            'amount' => 100
        ]);
        $this->assertEquals($reservation->fund->id, $transaction->fund->id);
        $this->assertEquals(100, $transaction->fund->balance);

        $transaction = $this->makeTransferFrom([
            'fund_id' => $reservation->fund->id,
            'amount' => -50
        ]);
        $this->assertEquals(50, $transaction->fund->balance);

        $transaction = $this->makeTransferTo([
            'fund_id' => $reservation->fund->id,
            'amount' => 25
        ]);
        $this->assertEquals(75, $transaction->fund->balance);
    }

    /** @test */
    public function it_updates_a_payment_balance_with_a_donation()
    {
        $reservation = $this->makeReservation();

        $due = $reservation->dues()->withBalance()->sortRecent()->first();
        $old_balance = $due->outstanding_balance;

        $this->makeDonation([
            'fund_id' => $reservation->fund->id,
            'amount' => 100
        ]);

        $payment = \App\Models\v1\Due::findOrFail($due->id);

        $this->assertNotEquals($old_balance, $payment->outstanding_balance);
        $this->assertEquals($old_balance - 100, $payment->outstanding_balance);
    }

    /** @test */
    public function it_updates_a_payment_balance_with_a_transfer_from()
    {
        $reservation = $this->makeReservation();

        $due = $reservation->dues()->withBalance()->sortRecent()->first();
        $old_balance = $due->outstanding_balance;

        $this->makeTransferFrom([
            'fund_id' => $reservation->fund->id,
            'amount' => -50
        ]);

        $payment = \App\Models\v1\Due::findOrFail($due->id);

        $this->assertNotEquals($old_balance, $payment->outstanding_balance);
        $this->assertEquals($old_balance + 50, $payment->outstanding_balance);
    }

    protected function makeReservation()
    {
        $reservation = factory(App\Models\v1\Reservation::class, 1)->create();
        event(new ReservationWasCreated($reservation, new Request));

        return $reservation;
    }

    protected function makeDonation(array $data)
    {
        $transaction = factory(App\Models\v1\Transaction::class, 'donation', 1)->create($data);
        event(new \App\Events\TransactionWasCreated($transaction));

        return $transaction;
    }

    protected function makeTransferFrom(array $data)
    {
        $transaction = factory(App\Models\v1\Transaction::class, 'transfer_from', 1)->create($data);
        event(new \App\Events\TransactionWasCreated($transaction));

        return $transaction;
    }

    protected function makeTransferTo(array $data)
    {
        $transaction = factory(App\Models\v1\Transaction::class, 'transfer_to', 1)->create($data);
        event(new \App\Events\TransactionWasCreated($transaction));

        return $transaction;
    }
}
