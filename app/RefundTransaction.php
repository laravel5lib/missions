<?php
namespace App;

use App\Events\TransactionWasCreated;
use Carbon\Carbon;
use Dingo\Api\Contract\Http\Request;

class RefundTransaction extends TransactionHandler
{

    /**
     * Create a new refund.
     *
     * @param Request $request
     * @return object
     */
    public function create($request)
    {
        $refundable = $this->transaction->findOrFail($request->get('transaction_id'));

        $refund_id = 'n/a';

        if (isset($refundable->details['charge_id'])) {
            $refund_id =  $this->merchant->refundCharge(
                $refundable->details['charge_id'],
                $request->get('amount'),
                [
                    'metadata' => [
                        'reason' => $request->get('reason')
                    ]
                ]
            );
        }

        $transaction = $this->transaction->create([
            'type' => 'refund',
            'amount' => -$request->get('amount'),
            'fund_id' => $refundable->fund_id,
            'details' => [
                'refund_id' => $refund_id,
                'reason' => $request->get('reason'),
                'transaction_id' => $request->get('transaction_id')
            ]
        ]);

        event(new TransactionWasCreated($transaction));

        return $transaction;
    }

    /**
     * Delete the refund.
     *
     * @param $id
     */
    public function destroy($id)
    {
        $refund = $this->transaction->findOrFail($id);
        $fund = $refund->fund;

        // Update the refund object
        if (isset($refund->details['refund_id'])) {
            $transaction = $this->transaction->findOrFail($refund->details['transaction_id']);
            $this->merchant->updateRefund(
                $transaction->details['charge_id'],
                $refund->details['refund_id'],
                [
                    'metadata' => [
                        'reason' => $refund->details['reason'],
                        'deleted_at' => Carbon::now(),
                    ]
                ]
            );
        }

        $refund->delete();

        $fund->reconcile();
    }
}
