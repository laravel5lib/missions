<?php
namespace App;

use App\Http\Requests\v1\Transactions\FeeRequest;

class FeeTransaction
{
    public function create()
    {
        app(FeeRequest::class)->validate();

        return 'processing fee';
    }
}