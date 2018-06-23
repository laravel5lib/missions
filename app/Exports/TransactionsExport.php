<?php

namespace App\Exports;

use App\Models\v1\Transaction;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use Maatwebsite\Excel\Concerns\FromQuery;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TransactionsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, ShouldQueue
{
    use Exportable;

    public function query()
    {
        return QueryBuilder::for(Transaction::class)
            ->allowedFilters([
                'description',
                Filter::exact('fund_id'),
                Filter::exact('type'),
                Filter::scope('amount'),
                Filter::scope('donor_name'),
                Filter::scope('donor_email'),
                Filter::scope('fund_name'),
                Filter::scope('payment_method'),
                Filter::scope('created_between'),
                Filter::scope('accounting_class'),
                Filter::scope('accounting_item')
            ])
            ->with(['fund.accounting-class, fund.accountin-item', 'donor'])
            ->defaultSort('-created_at')
            ->allowedSorts('type', 'amount', 'created_at');
    }

    public function headings(): array
    {
        return [
            'Date',
            'Transaction Type',
            'Amount',
            'Payment Method',
            'Class',
            'Item',
            'Description',
            'Fund Name',
            'Anonymous',
            'Donor First Name',
            'Donor Last Name',
            'Donor Company',
            'Donor Email',
            'Donor Phone',
            'Donor Address',
            'Donor City',
            'Donor State',
            'Donor Zip',
            'Donor Country',
            'Type',
            'Account'
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->created_at->toDateTimeString(),
            $transaction->type,
            $transaction->amountInDollars(),
            isset($transaction->details['type']) ? $transaction->details['type'] : null,
            optional($transaction->fund->accountingClass)->name,
            optional($transaction->fund->accountingItem)->name,
            $transaction->description,
            optional($transaction->fund)->name,
            $transaction->anonymous ? 'Yes' : 'No',
            optional($transaction->donor)->first_name,
            optional($transaction->donor)->last_name,
            optional($transaction->donor)->company,
            optional($transaction->donor)->email,
            optional($transaction->donor)->phone,
            optional($transaction->donor)->address,
            optional($transaction->donor)->city,
            optional($transaction->donor)->state,
            optional($transaction->donor)->zip,
            $transaction->donor ? country($transaction->donor->country_code) : null,
            'Sales Receipt',
            'Undeposited Funds'
        ];
    }
}