<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Facades\Excel;

class Exporter extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var $fields
     */
    protected $fields;
    /**
     * @var
     */
    protected $email;
    /**
     * @var
     */
    protected $fileName;

    /**
     * Create a new job instance.
     *
     * @param Collection $collection
     * @param array $fields
     * @param $email
     * @param null $fileName
     */
    public function __construct(Collection $collection, array $fields = [], $email, $fileName = null)
    {
        $this->collection = $collection;
        $this->fields = $fields;
        $this->email = $email;
        $this->fileName = $fileName ? $fileName . '_' . time() : time();
    }

    /**
     * Execute the job.
     * @param Mailer $mailer
     */
    public function handle(Mailer $mailer)
    {
        $data = $this->collection->map(function($collection) {
            return $this->filter($collection);
        })->all();

        $this->createExport($data, 'Funds')->sendEmail($mailer);
    }

    /**
     * Filter export file columns by fields.
     *
     * @param $collection
     * @return array
     */
    protected function filter($collection)
    {
        return collect($this->columns($collection))->filter(function($value, $key) {
            return in_array($key, $this->fields);
        })->all();
    }

    /**
     * Create the export file.
     *
     * @param $data
     * @param $sheetName
     * @return $this
     */
    protected function createExport($data, $sheetName)
    {
        Excel::create($this->fileName, function($excel) use($data, $sheetName) {

            $excel->sheet($sheetName, function($sheet) use($data) {

                $sheet->fromArray($data);
                $sheet->freezeFirstRow();

            });

        })->store('csv');

        return $this;
    }

    /**
     * Send the report as email attachment.
     *
     * @param $mailer
     */
    protected function sendEmail($mailer)
    {
        $data = ['file' => $this->fileName.'.csv'];

        $mailer->send('emails.reports.export', $data, function ($message) {
            $message->to($this->email);
            $message->subject('Your report is ready!');
            $message->attach(storage_path('exports/' . $this->fileName.'.csv'));
        });
    }

    /**
     * Get custom columns for export file.
     *
     * @param $collection
     * @return array
     */
    public function columns($collection)
    {
        return [];
    }
}
