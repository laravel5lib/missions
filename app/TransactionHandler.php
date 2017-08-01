<?php
namespace App;

use App\Models\v1\Donor;
use App\Models\v1\Fund;
use App\Models\v1\Transaction;
use App\Services\PaymentGateway;

class TransactionHandler
{

    public $transaction;
    public $merchant;
    public $donor;
    public $fund;

    /**
     * DonationTransaction constructor.
     * @param Transaction $transaction
     * @param PaymentGateway $merchant
     * @param Donor $donor
     * @param Fund $fund
     */
    public function __construct(Transaction $transaction, PaymentGateway $merchant, Donor $donor, Fund $fund)
    {
        $this->transaction = $transaction;
        $this->merchant = $merchant;
        $this->donor = $donor;
        $this->fund = $fund;
    }
}
