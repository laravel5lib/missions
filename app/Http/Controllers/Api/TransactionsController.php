<?php

namespace App\Http\Controllers\Api;

use App\CreditTransaction;
use App\RefundTransaction;
use App\DonationTransaction;
use App\TransferTransaction;
use Illuminate\Http\Request;
use App\Models\v1\Transaction;
use Spatie\QueryBuilder\Filter;
use App\Jobs\ExportTransactions;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Requests\v1\ExportRequest;
use App\Http\Requests\TransactionRequest;
use App\Http\Resources\TransactionResource;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Transformers\v1\TransactionTransformer;

class TransactionsController extends Controller
{
    /**
     * @var Transaction
     */
    private $transaction;

    /**
     * @var Request
     */
    private $request;

    /**
     * TransactionsController constructor.
     * @param Transaction $transaction
     * @param Request $request
     */
    public function __construct(Transaction $transaction, Request $request)
    {
        $this->transaction = $transaction;
        $this->request = $request;
    }
    
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
                Filter::exact('type'),
                Filter::exact('amount'),
                Filter::scope('donor_name'),
                Filter::scope('fund_name')
            ])
            ->allowedIncludes(['fund', 'donor'])
            ->paginate($request->input('per_page', 25));
        
        return TransactionResource::collection($transactions);
    }

    /**
     * Export transactions.
     *
     * @param ExportRequest $request
     * @return mixed
     */
    public function export(ExportRequest $request)
    {
        $this->dispatch(new ExportTransactions($request->all()));

        return $this->response()->created(null, [
            'message' => 'Report is being generated and will be available shortly.'
        ]);
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
        switch ($this->request->get('type')) {
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
