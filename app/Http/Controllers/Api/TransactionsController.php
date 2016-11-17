<?php

namespace App\Http\Controllers\Api;

use App\CreditTransaction;
use App\DonationTransaction;
use App\RefundTransaction;
use App\TransferTransaction;
use App\Models\v1\Transaction;
use App\Http\Controllers\Controller;
use App\Http\Transformers\v1\TransactionTransformer;
use Dingo\Api\Contract\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Facades\Excel;

class TransactionsController extends Controller
{
    // EXCEL:
        // Donor Name
        // Address
        // City, State, Zip
        // Country
        // Class
        // Item
        // Amount
        // Date

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
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
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
     * @param Request $request
     * @return mixed
     */
    public function export(Request $request)
    {
        $transactions = $this->transaction
                             ->filter($request->all())
                             ->with('fund')
                             ->get();

        $data = $transactions->map(function($transaction) {
            return [
                'description' => $transaction->description,
                'amount' => $transaction->amount,
                'class' => $transaction->fund->class,
                'item' => $transaction->fund->item
            ];
        });

        return Excel::create('transactions', function($excel) use($data) {

            $excel->sheet('Transactions', function($sheet) use($data) {

                $sheet->fromArray($data);
                $sheet->freezeFirstRow();

            });

        })->export('csv');
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
    public function store()
    {
        $transaction = $this->getTransactionHandler()->create($this->request);

        if ($transaction instanceof Collection) {
            return $this->response->collection($transaction, new TransactionTransformer);
        }

        return $this->response->item($transaction, new TransactionTransformer);
    }

    /**
     * Update a Transaction.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function update($id)
    {
        $transaction = $this->getTransactionHandler()->update($id, $this->request);

        return $this->response->item($transaction, new TransactionTransformer);
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
