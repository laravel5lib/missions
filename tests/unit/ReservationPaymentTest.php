<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\v1\Cost;
use App\Models\v1\Payment;
use App\Models\v1\Reservation;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReservationPaymentTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function adds_dues()
    {
        $cost = factory(Cost::class)->create(['amount' => 1000]);
        $payment = factory(Payment::class)->create([
            'cost_id' => $cost->id,
            'due_at' => '2019-01-01',
            'amount_owed' => 500,
            'percent_owed' => 50
        ]);

        $this->assertEquals('2019-01-01', $payment->due_at->toDateString());
        $this->assertEquals(50000, $payment->amount_owed);

        $reservation = factory(Reservation::class)->create();
        
        $data = $payment->get()->map(function ($pay) {
            return [
                'payment_id' => $pay->id,
                'due_at' => $pay->due_at,
                'grace_period' => $pay->grace_period,
                'outstanding_balance' => $pay->amount_owed
            ];
        });

        $reservation->payments()->addDues($data);
        
        $this->assertEquals(50000, $reservation->dues()->first()->outstanding_balance);
        $this->assertEquals('2019-01-01', $reservation->dues()->first()->due_at->toDateString());
    }

    /** @test */
    public function adds_upfront_dues()
    {
        $cost = factory(Cost::class)->create(['amount' => 1000]);
        $payment = factory(Payment::class)->create([
            'cost_id' => $cost->id,
            'due_at' => null,
            'amount_owed' => 500,
            'percent_owed' => 50
        ]);

        $this->assertNull($payment->due_at);
        $this->assertEquals(50000, $payment->amount_owed);

        $reservation = factory(Reservation::class)->create();
        
        $data = $payment->get()->map(function ($pay) {
            return [
                'payment_id' => $pay->id,
                'due_at' => $pay->due_at,
                'grace_period' => $pay->grace_period,
                'outstanding_balance' => $pay->amount_owed
            ];
        });

        $reservation->payments()->addDues($data);
        
        $this->assertEquals(50000, $reservation->dues()->first()->outstanding_balance);
        $this->assertEquals(Carbon::now()->toDateString(), $reservation->dues()->first()->due_at->toDateString());
    }
}
