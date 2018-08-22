<?php
namespace App;

use App\Events\TransactionWasCreated;
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
    public function create($request)
    {
        // abstract to custom validation rule
        $from_fund = $this->fund->withTrashed()->findOrFail($request->get('from_fund_id'));
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
            'related_transaction_id' => $from->id,
            'comment' => 'Transfer from ' . $from->fund->name
        ];
        $to->save();

        $from->details = [
            'related_transaction_id' => $to->id,
            'comment' => 'Transfer to ' . $to->fund->name
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
}
