<?php

namespace App\Jobs;

class ExportFunds extends Exporter
{
    public function columns($fund)
    {
        return [
            'name' => $fund->name,
            'type' => $fund->fundable_type,
            'balance' => $fund->balance,
            'class' => $fund->class,
            'item' => $fund->item,
            'created' => $fund->created_at->toDateTimeString()
        ];
    }
}
