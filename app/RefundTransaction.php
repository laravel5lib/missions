<?php
namespace App;

use App\Http\Requests\v1\Transactions\RefundRequest;
use Dingo\Api\Contract\Http\Request;

class RefundTransaction extends TransactionHandler
{
    public function create(Request $request)
    {
        $this->validate();

        $refundable = $this->transaction->findOrFail($request->get('transaction_id'));

        $refund_id = 'n/a';

        if (isset($refundable->payment['charge_id'])) {
            $refund_id =  $this->merchant->refundCharge(
                $refundable->payment['charge_id'],
                $request->get('amount')
            );
        }

        $transaction = $this->transaction->create([
            'type' => 'refund',
            'amount' => -$request->get('amount'),
            'fund_id' => $refundable->fund_id,
            'payment' => [
                'refund_id' => $refund_id,
                'reason' => $request->get('reason'),
                'transaction_id' => $request->get('transaction_id')
            ],
            'description' => 'Refund to ' . $refundable->fund->name
        ]);

        return $transaction;
    }

    /**
     * Validate the incoming request.
     */
    private function validate()
    {
        app(RefundRequest::class)->validate();
    }
}