<?php

namespace App\Http\Controllers\Api;

use App\CreditTransaction;
use App\RefundTransaction;
use App\DonationTransaction;
use App\TransferTransaction;
use Illuminate\Http\Request;
use App\Models\v1\Transaction;
use Spatie\QueryBuilder\Filter;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Requests\TransactionRequest;
use App\Http\Resources\TransactionResource;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Transformers\v1\TransactionTransformer;

class TransactionsController extends Controller
{    
    /**
     * Get transactions
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $transactions = QueryBuilder::for(Transaction::class)
            ->allowedFilters([
                'description',
                Filter::exact('fund_id'),
                Filter::exact('type'),
                Filter::scope('amount'),
                Filter::scope('donor_name'),
                Filter::scope('donor_email'),
                Filter::scope('fund_name'),
                Filter::scope('payment_method'),
                Filter::scope('card_last_four'),
                Filter::scope('created_between'),
                Filter::scope('accounting_class'),
                Filter::scope('accounting_item')
            ])
            ->allowedIncludes(['fund.accounting-class', 'fund.accounting-item', 'donor'])
            ->defaultSort('-created_at')
            ->allowedSorts('type', 'amount', 'created_at')
            ->paginate($request->input('per_page', 50));
        
        return TransactionResource::collection($transactions);
    }

    /**
     * Get a transaction by it's id.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $transaction = Transaction::findOrFail($id);

        $transaction->load(['fund', 'donor']);

        return new TransactionResource($transaction);
    }

    /**
     * Create a new Transaction.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function store(TransactionRequest $request)
    {
        $transaction = $this->getTransactionHandler()->create($request);

        if ($transaction instanceof Collection) {
            return $this->response->collection($transaction, new TransactionTransformer);
        }

        return $this->response->item($transaction, new TransactionTransformer);
    }

    /**
     * Update a Transaction.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function update(TransactionRequest $request, $id)
    {
        $transaction = $this->getTransactionHandler()->update($id, $request);

        return $this->response->item($transaction, new TransactionTransformer);
    }

    /**
     * Delete a transaction.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        $this->getTransactionHandler()->destroy($id);

        return $this->response->noContent();
    }

    /**
     * Get the handler for the transaction type.
     *
     * @return mixed
     */
    private function getTransactionHandler()
    {
        switch (request()->get('type')) {
            case 'donation':
                return app()->make(DonationTransaction::class);
                break;
            case 'transfer':
                return app()->make(TransferTransaction::class);
                break;
            case 'refund':
                return app()->make(RefundTransaction::class);
                break;
            case 'credit':
                return app()->make(CreditTransaction::class);
                break;
            default:
                return app()->make(DonationTransaction::class);
        }
    }
}
