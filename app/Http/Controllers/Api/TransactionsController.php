<?php

namespace App\Http\Controllers\Api;

use App\Http\Transformers\v1\TransactionTransformer;
use App\Models\v1\Transaction;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TransactionsController extends Controller
{

    /**
     * @var Transaction
     */
    private $transaction;

    /**
     * TransactionsController constructor.
     * @param Transaction $transaction
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function index(Request $request)
    {
        $transactions = $this->transaction
            ->filter($request->all())
            ->paginate($request->get('per_page', 10));

        return $this->response->paginator($transactions, new TransactionTransformer);
    }

    public function show($id)
    {
        $transaction = $this->transaction->findOrFail($id);

        return $this->response->item($transaction, new TransactionTransformer);
    }
}
