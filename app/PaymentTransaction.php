<?php
namespace App;

use App\Http\Requests\v1\Transactions\PaymentRequest;

class PaymentTransaction
{
    public function create()
    {
        app(PaymentRequest::class)->validate();

        return 'processing payment';
    }
}