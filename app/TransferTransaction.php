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

        $from_fund = $this->fund->findOrFail($request->get('from_fund_id'));

        if ($from_fund->balance < $request->get('amount')) {
            throw new ValidationHttpException(['Insufficient funds.']);
        }

        $to_fund = $this->fund->findOrFail($request->get('to_fund_id'));

        // Transfer from
        $from = $this->transaction->create([
            'type' => 'transfer',
            'description' => 'Transfer from ' . $from_fund->name,
            'amount' => -$request->get('amount'),
            'fund_id' => $from_fund->id,
            'payment' => [
                'to' => [
                    'fund_id' => $to_fund->id,
                    'fund_name' => $to_fund->name
                ]
            ]
        ]);

        event(new TransactionWasCreated($from));

        // Transfer to
        $to = $this->transaction->create([
            'type' => 'transfer',
            'description' => 'Transfer to ' . $to_fund->name,
            'amount' => $request->get('amount'),
            'fund_id' => $to_fund->id,
            'payment' => [
                'from' => [
                    'fund_id' => $from_fund->id,
                    'fund_name' => $from_fund->name
                ]
            ]
        ]);

        event(new TransactionWasCreated($to));

        return collect([$from, $to]);

    }

    /**
     * Validate the incoming request.
     */
    private function validate()
    {
        app(TransferRequest::class)->validate();
    }

}