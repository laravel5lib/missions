<?php
namespace App;

use App\Models\v1\Donor;
use App\Models\v1\Transaction;
use App\Services\PaymentGateway;

class TransactionHandler {

    public $transaction;
    public $merchant;
    public $donor;

    /**
     * DonationTransaction constructor.
     * @param Transaction $transaction
     * @param PaymentGateway $merchant
     * @param Donor $donor
     */
    public function __construct(Transaction $transaction, PaymentGateway $merchant, Donor $donor)
    {
        $this->transaction = $transaction;
        $this->merchant = $merchant;
        $this->donor = $donor;
    }
}