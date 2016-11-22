<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Facades\Excel;

class ExportTransactions extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    /**
     * @var Collection
     */
    private $transactions;

    /**
     * @var $fields
     */
    private $fields;
    /**
     * @var
     */
    private $email;
    /**
     * @var
     */
    private $fileName;

    /**
     * Create a new job instance.
     *
     * @param Collection $transactions
     * @param array $fields
     * @param $email
     * @param null $fileName
     */
    public function __construct(Collection $transactions, array $fields = [], $email, $fileName = null)
    {
        $this->transactions = $transactions;
        $this->fields = $fields;
        $this->email = $email;
        $this->fileName = $fileName ? $fileName . '_' . time() : time();
    }

    /**
     * Execute the job.
     *
     * @param Mailer $mailer
     */
    public function handle(Mailer $mailer)
    {
        $data = $this->transactions->map(function($transaction) {
                    return $this->getColumns($transaction);
                })->all();

        $this->createExport($data)->sendEmail($mailer);
    }

    private function getColumns($transaction)
    {
        $columns = [
            'description' => $transaction->description,
            'type' => $transaction->type,
            'amount' => $transaction->amount,
            'class' => $transaction->fund->class,
            'item' => $transaction->fund->item,
            'date' => $transaction->created_at->toDateTimeString(),
            'fund_name' => $transaction->fund->name,
            'payment_type' => isset($transaction->payment['type']) ? $transaction->payment['type'] : null,
            'donor_name' => null,
            'donor_company' => null,
            'donor_email' => null,
            'donor_phone' => null,
            'donor_address_one' => null,
            'donor_address_two' => null,
            'donor_country' => null
        ];

        if ($transaction->donor) {
            $columns['donor_name'] = $transaction->donor->name;
            $columns['donor_company'] = $transaction->donor->company;
            $columns['donor_email'] = $transaction->donor->email;
            $columns['donor_phone'] = $transaction->donor->phone;
            $columns['donor_address_one'] = $transaction->donor->address;
            $columns['donor_address_two'] = $transaction->donor->city. ',' . $transaction->donor->state. ',' . $transaction->donor->zip;
            $columns['donor_country'] = country($transaction->donor->country_code);
        }

        return collect($columns)->filter(function($value, $key) {
            return in_array($key, $this->fields);
        })->all();
    }

    private function createExport($data)
    {
        Excel::create($this->fileName, function($excel) use($data) {

            $excel->sheet('Transactions', function($sheet) use($data) {

                $sheet->fromArray($data);
                $sheet->freezeFirstRow();

            });

        })->store('csv');

        return $this;
    }

    private function sendEmail($mailer)
    {
        $data = ['file' => $this->fileName.'.csv'];

        $mailer->send('emails.reports.export', $data, function ($message) {
            $message->to($this->email);
            $message->subject('Your report is ready!');
            $message->attach(storage_path('exports/' . $this->fileName.'.csv'));
        });
    }
}
