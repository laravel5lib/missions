<?php

namespace App\Http\Controllers\Api;

use App\CreditTransaction;
use App\RefundTransaction;
use App\DonationTransaction;
use App\TransferTransaction;
use App\Models\v1\Transaction;
use App\Jobs\ExportTransactions;
use App\Http\Controllers\Controller;
use Dingo\Api\Contract\Http\Request;
use App\Http\Requests\v1\ExportRequest;
use App\Http\Requests\TransactionRequest;
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
     * Get all transactions.
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $transactions = $this->transaction
                             ->filter($request->all())
                             ->paginate($request->get('per_page', 10));

        return $this->response->paginator($transactions, new TransactionTransformer);
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
        $transaction = $this->transaction->findOrFail($id);

        return $this->response->item($transaction, new TransactionTransformer);
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
