<?php
namespace App;

use App\Http\Requests\v1\Transactions\TransferRequest;
use Dingo\Api\Contract\Http\Request;

class TransferTransaction extends TransactionHandler
{
    public function create(Request $request)
    {
        app(TransferRequest::class)->validate();

        // Transfer from
        $from = $this->transaction->create([
            'amount' => $request->get('amount'),
            'fund_id' => $request->get('from_fund_id')
        ]);

        // Transfer to
        $to = $this->transaction->create([
            'amount' => $request->get('amount'),
            'fund_id' => $request->get('to_fund_id')
        ]);

        return $from->merge($to);

    }
}