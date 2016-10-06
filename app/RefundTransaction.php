<?php
namespace App;

use App\Http\Requests\v1\Transactions\RefundRequest;

class RefundTransaction
{
    public function create()
    {
        app(RefundRequest::class)->validate();
        return 'processing refund';
    }
}