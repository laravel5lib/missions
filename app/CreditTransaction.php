<?php
namespace App;

use App\Events\TransactionWasCreated;
use App\Http\Requests\v1\Transactions\CreditRequest;
use Dingo\Api\Contract\Http\Request;

class CreditTransaction extends TransactionHandler
{

    /**
     * @param Request $request
     * @return object
     */
    public function create(Request $request)
    {
        $this->validate();

        $transaction = $this->transaction->create([
            'type' => 'credit',
            'amount' => $request->get('amount'),
            'fund_id' => $request->get('fund_id'),
            'details' => ['reason' => $request->get('reason')]
        ]);

        event(new TransactionWasCreated($transaction));

        return $transaction;
    }

    /**
     * Delete a credit.
     *
     * @param $id
     */
    public function destroy($id)
    {
        $transaction = $this->transaction->findOrFail($id);
        $fund = $transaction->fund;

        $transaction->delete();

        $fund->reconcile();
    }

    /**
     * Validate incoming request.
     */
    private function validate()
    {
        app(CreditRequest::class)->validate();
    }

}