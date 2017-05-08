<?php

namespace App\Jobs;

use App\Models\v1\Fund;

class ExportFunds extends Exporter
{
    public function data(array $request)
    {
        return Fund::filter($request)->get();
    }

    public function columns($fund)
    {
        return [
            'name' => $fund->name,
            'type' => $fund->fundable_type,
            'balance' => $fund->balanceInDollars(),
            'class' => $fund->accountingClass->name,
            'item' => $fund->accountingItem->name,
            'created' => $fund->created_at->toDateTimeString()
        ];
    }
}
