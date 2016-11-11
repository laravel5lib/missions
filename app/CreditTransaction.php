<?php
namespace App;

use App\Http\Requests\v1\Transactions\CreditRequest;
use Dingo\Api\Contract\Http\Request;

class CreditTransaction extends TransactionHandler
{
    public function create(Request $request)
    {
        $this->validate();

        $fund = $this->fund->findOrFail($request->get('fund_id'));

        $transaction = $this->transaction->create([
            'type' => 'credit',
            'amount' => -$request->get('amount'),
            'fund_id' => $fund->id,
            'payments' => ['reason' => $request->get('reason')],
            'description' => 'Credit to ' . $fund->name
        ]);

        return $transaction;
    }

    private function validate()
    {
        app(CreditRequest::class)->validate();
    }

}