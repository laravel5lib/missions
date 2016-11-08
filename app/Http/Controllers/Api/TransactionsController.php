<?php

namespace App\Http\Controllers\Api;

use App\DonationTransaction;
use App\FeeTransaction;
use App\PaymentTransaction;
use App\RefundTransaction;
use App\TransferTransaction;
use App\Models\v1\Transaction;
use App\Http\Controllers\Controller;
use App\Http\Transformers\v1\TransactionTransformer;
use Dingo\Api\Contract\Http\Request;

class TransactionsController extends Controller
{
    // General Donations:
        // CLASS: General
        // ITEM: General Donation

    // Campaign Donations:
        // CLASS: {{ campaign_name }} - General
        // ITEM: General Donation

    // Trip/Reservation Donations:
        // CLASS: {{ campaign_name }} - Team
        // ITEM: Missionary Donation

    // Cause Donations:
        // CLASS: Angel Houses -OR- Wells -OR- Freedom Houses -OR- {{ campaign_name }} - Stadium Outreach &amp; Humanitarian
        // ITEM: General Donation

    // Project Donations:
        // CLASS: Angel Houses -OR- Wells -OR- Freedom Houses -OR- {{ campaign_name }} - Stadium Outreach &amp; Humanitarian
        // ITEM: {{ project_name }} Donation

    // Student Tuition Payments:
        // CLASS: MMC
        // ITEM: Tuition & Fees


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
     * @return \Dingo\Api\Http\Response
     */
    public function index()
    {
        $transactions = $this->transaction
                             ->filter($this->request->all())
                             ->paginate($this->request->get('per_page', 10));

        return $this->response->paginator($transactions, new TransactionTransformer);
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

//        return $transaction;

        return $this->response->collection($transaction, new TransactionTransformer);
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
            default:
                return app()->make(DonationTransaction::class);
        }
    }
}
