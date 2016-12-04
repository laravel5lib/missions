<?php
namespace App;

use App\Events\TransactionWasCreated;
use App\Http\Requests\v1\Transactions\TransferRequest;
use Dingo\Api\Contract\Http\Request;
use Dingo\Api\Exception\ValidationHttpException;

class TransferTransaction extends TransactionHandler
{

    /**
     * Create two transactions.
     *
     * @param Request $request
     * @return \Illuminate\Support\Collection
     */
    public function create(Request $request)
    {
        $this->validate();

        // abstract to custom validation rule
        $from_fund = $this->fund->findOrFail($request->get('from_fund_id'));
        if ($from_fund->balance < $request->get('amount')) {
            throw new ValidationHttpException(['Insufficient funds.']);
        }

        // Transfer from
        $from = $this->transaction->create([
            'type' => 'transfer',
            'amount' => -$request->get('amount'),
            'fund_id' => $request->get('from_fund_id')
        ]);

        event(new TransactionWasCreated($from));

        // Transfer to
        $to = $this->transaction->create([
            'type' => 'transfer',
            'amount' => $request->get('amount'),
            'fund_id' => $request->get('to_fund_id')
        ]);

        event(new TransactionWasCreated($to));

        $to->details = [
            'related_transaction_id' => $from->id
        ];
        $to->save();

        $from->details = [
            'related_transaction_id' => $to->id
        ];
        $from->save();

        return collect([$from, $to]);
    }

    /**
     * Delete a transfer.
     *
     * @param $id
     */
    public function destroy($id)
    {
        $transaction = $this->transaction->findOrFail($id);
        $related = $transaction->details['related_transaction_id'];
        $fund = $transaction->fund;
        $transaction->delete();
        $fund->reconcile();

        $relatedTransaction = $this->transaction->findOrFail($related);
        $relatedFund = $relatedTransaction->fund;
        $relatedTransaction->delete();
        $relatedFund->reconcile();
    }

    /**
     * Validate the incoming request.
     */
    private function validate()
    {
        app(TransferRequest::class)->validate();
    }

}