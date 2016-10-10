<?php
namespace App;

use App\Http\Requests\v1\Transactions\RefundRequest;
use Dingo\Api\Contract\Http\Request;

class RefundTransaction extends TransactionHandler
{
    public function create(Request $request)
    {
        app(RefundRequest::class)->validate();

        $refund_id =  $this->merchant->refundCharge(
            $request->get('charge_id'),
            $request->get('amount')
        );

        $transaction = $this->transaction->create([
            'type' => 'refund',
            'amount' => $request->get('amount'),
            'fund_id' => $request->get('fund_id'),
            'payments' => ['refund_id' => $refund_id],
            'description' => $request->get('description')
        ]);

        return $transaction;
    }
}