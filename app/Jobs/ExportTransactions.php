<?php

namespace App\Jobs;

use App\Models\v1\Transaction;

class ExportTransactions extends Exporter
{
    public function data(array $request)
    {
        $transactions = Transaction::filter($request)
            ->with('donor', 'fund')
            ->get();

        return $transactions;
    }

    public function columns($transaction)
    {
        $columns = [
            'description' => $transaction->description,
            'transaction_type' => $transaction->type,
            'amount' => $transaction->amountInDollars(),
            'class' => $transaction->fund->class,
            'item' => $transaction->fund->item,
            'date' => $transaction->created_at->toDateTimeString(),
            'fund_name' => $transaction->fund->name,
            'payment_type' => isset($transaction->details['type']) ? $transaction->details['type'] : null,
            'anonymous' => $transaction->anonymous ? 'Yes' : 'No',
            'donor_name' => null,
            'donor_company' => null,
            'donor_email' => null,
            'donor_phone' => null,
            'donor_address_one' => null,
            'donor_address_two' => null,
            'donor_country' => null,
            'type' => 'Sales Receipt',
            'account' => 'Undeposited Funds'
        ];

        if ($transaction->donor) {
            $columns['donor_name'] = $transaction->donor->name;
            $columns['donor_company'] = $transaction->donor->company;
            $columns['donor_email'] = $transaction->donor->email;
            $columns['donor_phone'] = $transaction->donor->phone;
            $columns['donor_address_one'] = $transaction->donor->address;
            $columns['donor_address_two'] = $transaction->donor->city. ', ' . $transaction->donor->state. ' ' . $transaction->donor->zip;
            $columns['donor_country'] = country($transaction->donor->country_code);
        }

        return $columns;
    }
}
